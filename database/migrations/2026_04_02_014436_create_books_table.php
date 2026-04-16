<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('author');
            $table->string('isbn')->unique();
            $table->string('publisher');
            $table->year('year');
            $table->string('category');
            $table->integer('stock')->default(0);
            $table->string('cover')->nullable(); // Path untuk gambar
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }
};
