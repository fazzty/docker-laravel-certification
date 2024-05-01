<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Author;

class AuthorBookTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $books = Book::all();
        $authors = Author::all();

        foreach ($books as $book) {
            $authorIds = $authors
                ->random(2)
                ->pluck('id')
                ->all();

            $book->authors()->attach($authorIds);

            
        }
    }
}
