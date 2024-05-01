<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Book;
use App\Models\Category;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Book::factory(3)->create();
        $categories = [
            Category::factory()->create(['title' => 'programing']),
            Category::factory()->create(['title' => 'design']),
            Category::factory()->create(['title' => 'managemnt']),
        ];

        foreach ($categories as $category){
            Book::factory(2)
                ->create(['category_id' => $category->id]);
        }
    }
}
