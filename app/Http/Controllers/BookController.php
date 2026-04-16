<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class BookController extends Controller
{
    /**
     * Display all books
     */
    public function index(Request $request)
    {
        $isAdmin = auth()->user()->role === 'admin';
        $search = $request->query('search', '');
        $category = $request->query('category', '');

        $query = Book::query();

        // Admin melihat semua buku (termasuk yang dihapus)
        if ($isAdmin) {
            $query->withTrashed();
        }

        // Search by title, author, or isbn
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('isbn', 'LIKE', "%{$search}%");
            });
        }

        // Filter by category
        if ($category) {
            $query->where('category', $category);
        }

        $books = $query->orderBy('title', 'asc')->get();

        // Get unique categories for filter
        $categories = Book::distinct()->pluck('category')->sort()->values();

        return Inertia::render('Books/Index', [
            'books' => $books,
            'categories' => $categories,
            'isAdmin' => $isAdmin,
            'search' => $search,
            'selectedCategory' => $category,
        ]);
    }

    /**
     * Show book detail (API)
     */
    public function show(Book $book)
    {
        return response()->json($book->load('loanItems'));
    }

    /**
     * Serve book cover files from storage or public/images.
     */
    public function cover(Request $request): BinaryFileResponse
    {
        $path = trim((string) $request->query('path', ''));

        if ($path === '') {
            abort(404);
        }

        $path = str_replace('\\', '/', ltrim($path, '/'));

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://') || str_starts_with($path, '//') || str_starts_with($path, 'data:')) {
            abort(404);
        }

        $candidates = [];

        if (str_starts_with($path, 'public/storage/')) {
            $candidates[] = storage_path('app/public/' . substr($path, strlen('public/storage/')));
        }

        if (str_starts_with($path, 'storage/')) {
            $candidates[] = storage_path('app/public/' . substr($path, strlen('storage/')));
        } elseif (str_starts_with($path, 'images/')) {
            $candidates[] = public_path($path);
        } else {
            $candidates[] = storage_path('app/public/' . $path);
            $candidates[] = public_path($path);
        }

        foreach (array_unique($candidates) as $candidate) {
            if (is_file($candidate)) {
                return response()->file($candidate);
            }
        }

        abort(404);
    }

    /**
     * Show form to create new book
     */
    public function create()
    {
        return Inertia::render('Books/Create');
    }

    /**
     * Show form to edit book
     */
    public function edit(Book $book)
    {
        return Inertia::render('Books/Edit', ['book' => $book]);
    }

    /**
     * Show form to edit stock only
     */
    public function editStock(Book $book)
    {
        return Inertia::render('Books/EditStock', ['book' => $book]);
    }

    /**
     * Store new book (Admin only)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'isbn' => 'required|unique:books|string|max:20',
            'publisher' => 'required|string|max:255',
            'year' => 'required|integer|min:1000|max:' . date('Y'),
            'category' => 'required|string|max:100',
            'stock' => 'required|integer|min:0',
            'description' => 'nullable|string|max:1000',
            'cover' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_path' => 'nullable|string|max:255',
        ]);

        if ($request->hasFile('cover')) {
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        } elseif (!empty($validated['cover_path'])) {
            $validated['cover'] = $this->normalizeCoverPath($validated['cover_path']);
        }

        unset($validated['cover_path']);

        Book::create($validated);

        return redirect()->route('books.index')
            ->with('success', "Buku '{$validated['title']}' berhasil ditambahkan.");
    }

    /**
     * Update book (Admin only)
     */
    public function update(Request $request, Book $book)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'author' => 'sometimes|string|max:255',
            'isbn' => 'sometimes|string|max:20|unique:books,isbn,' . $book->id,
            'publisher' => 'sometimes|string|max:255',
            'year' => 'sometimes|integer|min:1000|max:' . date('Y'),
            'category' => 'sometimes|string|max:100',
            'stock' => 'sometimes|integer|min:0',
            'description' => 'sometimes|nullable|string|max:1000',
            'cover' => 'sometimes|nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'cover_path' => 'sometimes|nullable|string|max:255',
        ]);

        if ($request->hasFile('cover')) {
            $this->deleteStoredCover($book->cover);
            $validated['cover'] = $request->file('cover')->store('covers', 'public');
        } elseif (array_key_exists('cover_path', $validated) && $validated['cover_path'] !== null && $validated['cover_path'] !== '') {
            $this->deleteStoredCover($book->cover);
            $validated['cover'] = $this->normalizeCoverPath($validated['cover_path']);
        }

        unset($validated['cover_path']);

        $book->update($validated);

        return back()->with('success', 'Buku berhasil diperbarui.');
    }

    /**
     * Update stock only
     */
    public function updateStock(Request $request, Book $book)
    {
        $validated = $request->validate([
            'stock' => 'required|integer|min:0',
        ]);

        $book->update([
            'stock' => $validated['stock'],
        ]);

        return back()->with('success', 'Stok buku berhasil diperbarui.');
    }

    /**
     * Delete book (soft delete - Admin only)
     */
    public function destroy(Book $book)
    {
        $title = $book->title;
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', "Buku '$title' berhasil dihapus. Gunakan 'Urungkan' untuk memulihkan.");
    }

    /**
     * Restore deleted book (Admin only)
     */
    public function restore($id)
    {
        $book = Book::withTrashed()->findOrFail($id);
        $title = $book->title;
        $book->restore();

        return redirect()->route('books.index')
            ->with('success', "Buku '$title' berhasil dipulihkan.");
    }

    /**
     * Permanently delete book (Admin only)
     */
    public function forceDelete($id)
    {
        $book = Book::withTrashed()->findOrFail($id);
        $title = $book->title;

        $this->deleteStoredCover($book->cover);

        $book->forceDelete();

        return redirect()->route('books.index')
            ->with('success', "Buku '$title' berhasil dihapus secara permanen.");
    }

    private function deleteStoredCover(?string $cover): void
    {
        if (!$cover) {
            return;
        }

        $coverPath = ltrim($cover, '/');

        if (str_starts_with($coverPath, 'images/')) {
            return;
        }

        $storagePath = str_starts_with($coverPath, 'storage/')
            ? substr($coverPath, strlen('storage/'))
            : $coverPath;

        if (Storage::disk('public')->exists($storagePath)) {
            Storage::disk('public')->delete($storagePath);
        }
    }

    private function normalizeCoverPath(string $coverPath): string
    {
        $coverPath = trim($coverPath);
        $coverPath = str_replace('\\', '/', $coverPath);

        if (str_starts_with($coverPath, 'public/storage/')) {
            return '/' . substr($coverPath, strlen('public/'));
        }

        if (str_starts_with($coverPath, 'storage/')) {
            return '/' . $coverPath;
        }

        if (str_starts_with($coverPath, 'images/')) {
            return '/' . $coverPath;
        }

        if (str_starts_with($coverPath, '/storage/') || str_starts_with($coverPath, '/images/')) {
            return $coverPath;
        }

        return '/' . ltrim($coverPath, '/');
    }
}
