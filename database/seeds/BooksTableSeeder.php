<?php
use App\Models\Book;
use Illuminate\Database\Seeder;

class BooksTableSeeder extends Seeder
{
    /**
     * Run the books table data seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate existing record in book table
        Book::truncate();

        $faker = \Faker\Factory::create();

        // Create dummy records in our table books:
        for ($i = 0; $i < 50; $i++) {
            Book::create([
                'title' => $faker->title,
                'author' => $faker->name,
            ]);
        }
    }
}
