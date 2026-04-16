<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $books = [
            [
                'title' => 'Sapiens: A Brief History of Humankind',
                'author' => 'Yuval Noah Harari',
                'isbn' => '978-0062316097',
                'publisher' => 'Harper',
                'year' => 2014,
                'category' => 'History',
                'stock' => 5,
                'cover' => '/images/Sapiens.webp',
                'description' => 'A sweeping story of how Homo sapiens came to dominate the world.'
            ],
            [
                'title' => 'The Midnight Library',
                'author' => 'Matt Haig',
                'isbn' => '978-0020195719',
                'publisher' => 'Viking',
                'year' => 2020,
                'category' => 'Fiction',
                'stock' => 4,
                'cover' => '/images/themidnight.jpg',
                'description' => 'A novel about all the choices that go into a life well lived.'
            ],
            [
                'title' => '1984',
                'author' => 'George Orwell',
                'isbn' => '978-0451524935',
                'publisher' => 'Signet Classics',
                'year' => 1949,
                'category' => 'Dystopian',
                'stock' => 3,
                'cover' => '/images/1984.jpg',
                'description' => 'A dystopian novel about a totalitarian society.'
            ],
            [
                'title' => 'To Kill a Mockingbird',
                'author' => 'Harper Lee',
                'isbn' => '978-0061120084',
                'publisher' => 'HarperCollins',
                'year' => 1960,
                'category' => 'Literature',
                'stock' => 4,
                'cover' => '/images/tokill.jpg',
                'description' => 'A gripping tale of racial injustice and childhood innocence.'
            ],
            [
                'title' => 'Atomic Habits',
                'author' => 'James Clear',
                'isbn' => '978-0735211292',
                'publisher' => 'Avery',
                'year' => 2018,
                'category' => 'Self-Help',
                'stock' => 6,
                'cover' => '/images/atomic.jpg',
                'description' => 'Transform your life through tiny changes.'
            ],
            [
                'title' => 'The Lean Startup',
                'author' => 'Eric Ries',
                'isbn' => '978-0307887894',
                'publisher' => 'Crown Business',
                'year' => 2011,
                'category' => 'Business',
                'stock' => 3,
                'cover' => '/images/thelean.jpg',
                'description' => 'How today\'s entrepreneurs use continuous innovation.'
            ],
            [
                'title' => 'Dune',
                'author' => 'Frank Herbert',
                'isbn' => '978-0441172719',
                'publisher' => 'Ace',
                'year' => 1965,
                'category' => 'Science Fiction',
                'stock' => 4,
                'cover' => '/images/dune.webp',
                'description' => 'An epic saga of politics, religion, and ecology.'
            ],
            [
                'title' => 'The Great Gatsby',
                'author' => 'F. Scott Fitzgerald',
                'isbn' => '978-0743273565',
                'publisher' => 'Scribner',
                'year' => 1925,
                'category' => 'Classic',
                'stock' => 5,
                'cover' => '/images/thegreat.jpg',
                'description' => 'A tale of obsessive love and the American Dream.'
            ],
            [
                'title' => 'Python for Everybody',
                'author' => 'Charles Severance',
                'isbn' => '978-1511885935',
                'publisher' => 'CreateSpace',
                'year' => 2014,
                'category' => 'Programming',
                'stock' => 7,
                'cover' => '/images/python.jpg',
                'description' => 'Exploring data with Python for beginners.'
            ],
            [
                'title' => 'Clean Code',
                'author' => 'Robert C. Martin',
                'isbn' => '978-0132350884',
                'publisher' => 'Prentice Hall',
                'year' => 2008,
                'category' => 'Programming',
                'stock' => 4,
                'cover' => '/images/cleancode.webp',
                'description' => 'A handbook of agile software craftsmanship.'
            ],
            [
                'title' => 'The Hobbit',
                'author' => 'J.R.R. Tolkien',
                'isbn' => '978-0547928227',
                'publisher' => 'Mariner Books',
                'year' => 1937,
                'category' => 'Fantasy',
                'stock' => 5,
                'cover' => '/images/thehobbit.jpg',
                'description' => 'A fantasy adventure of a hobbit\'s unexpected journey.'
            ],
            [
                'title' => 'Thinking, Fast and Slow',
                'author' => 'Daniel Kahneman',
                'isbn' => '978-0374533557',
                'publisher' => 'Farrar, Straus and Giroux',
                'year' => 2011,
                'category' => 'Psychology',
                'stock' => 3,
                'cover' => '/images/thinking.jpg',
                'description' => 'Insights into the two systems that drive the way we think.'
            ],
            [
                'title' => 'The Catcher in the Rye',
                'author' => 'J.D. Salinger',
                'isbn' => '978-0316769174',
                'publisher' => 'Little, Brown',
                'year' => 1951,
                'category' => 'Literature',
                'stock' => 2,
                'cover' => '/images/thecatcher.jpg',
                'description' => 'A story of teenage alienation and angst.'
            ],
            [
                'title' => 'Educated',
                'author' => 'Tara Westover',
                'isbn' => '978-0399590504',
                'publisher' => 'Penguin Press',
                'year' => 2018,
                'category' => 'Biography',
                'stock' => 4,
                'cover' => '/images/educated.jpg',
                'description' => 'A memoir of extraordinary journey to education and self-invention.'
            ],
            [
                'title' => 'The Design of Everyday Things',
                'author' => 'Don Norman',
                'isbn' => '978-0465050659',
                'publisher' => 'Basic Books',
                'year' => 2013,
                'category' => 'Design',
                'stock' => 3,
                'cover' => '/images/thedesign.jpg',
                'description' => 'Principles of good design and poor design.'
            ],
            [
                'title' => 'Mindset',
                'author' => 'Carol S. Dweck',
                'isbn' => '978-0345472328',
                'publisher' => 'Ballantine Books',
                'year' => 2006,
                'category' => 'Self-Help',
                'stock' => 5,
                'cover' => '/images/mindset.jpg',
                'description' => 'The new psychology of success.'
            ],
            [
                'title' => 'Foundation',
                'author' => 'Isaac Asimov',
                'isbn' => '978-0553293357',
                'publisher' => 'Bantam Classics',
                'year' => 1951,
                'category' => 'Science Fiction',
                'stock' => 3,
                'cover' => '/images/foundation.jpg',
                'description' => 'A story of galactic empire and psychohistory.'
            ],
            [
                'title' => 'The Silent Patient',
                'author' => 'Alex Michaelides',
                'isbn' => '978-1250295522',
                'publisher' => 'Hachette Book Group',
                'year' => 2019,
                'category' => 'Thriller',
                'stock' => 4,
                'cover' => '/images/thesilent.jpg',
                'description' => 'A woman shoots her husband and never speaks again.'
            ],
            [
                'title' => 'Deep Work',
                'author' => 'Cal Newport',
                'isbn' => '978-0349411903',
                'publisher' => 'Grand Central Publishing',
                'year' => 2016,
                'category' => 'Business',
                'stock' => 4,
                'cover' => '/images/deep.jpg',
                'description' => 'Rules for focused success in a distracted world.'
            ],
            [
                'title' => 'The Book Thief',
                'author' => 'Markus Zusak',
                'isbn' => '978-0375831003',
                'publisher' => 'Knopf',
                'year' => 2005,
                'category' => 'Historical Fiction',
                'stock' => 4,
                'cover' => '/images/thebook.jpg',
                'description' => 'A story set during Nazi Germany told by Death itself.'
            ],
            [
                'title' => 'The Alchemist',
                'author' => 'Paulo Coelho',
                'isbn' => '978-0061122415',
                'publisher' => 'HarperOne',
                'year' => 1988,
                'category' => 'Fiction',
                'stock' => 5,
                'cover' => '/images/thealchemist.jpg',
                'description' => 'A fable about following your dreams and listening to your heart.'
            ],
            [
                'title' => 'The Library Book',
                'author' => 'Susan Orlean',
                'isbn' => '978-1476757004',
                'publisher' => 'Simon & Schuster',
                'year' => 2018,
                'category' => 'Nonfiction',
                'stock' => 4,
                'cover' => '/images/thelibrarybook.jpg',
                'description' => 'A story about libraries, memory, fire, and the people who keep books alive.'
            ],
            [
                'title' => 'How to Read a Book',
                'author' => 'Mortimer J. Adler',
                'isbn' => '978-0671212094',
                'publisher' => 'Touchstone',
                'year' => 1940,
                'category' => 'Education',
                'stock' => 3,
                'cover' => '/images/howtoreadbook.jpg',
                'description' => 'A classic guide to reading with focus, insight, and comprehension.'
            ],
            [
                'title' => 'The Little Prince',
                'author' => 'Antoine de Saint-Exupery',
                'isbn' => '978-0156012195',
                'publisher' => 'Harcourt',
                'year' => 1943,
                'category' => 'Classic',
                'stock' => 6,
                'cover' => '/images/thelittleprince.jpg',
                'description' => 'A poetic tale about wonder, friendship, and seeing with the heart.'
            ]
        ];

        foreach ($books as $book) {
            Book::updateOrCreate(
                ['isbn' => $book['isbn']],
                $book
            );
        }
    }
}

