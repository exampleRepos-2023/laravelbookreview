<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Review;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     */
    public function run(): void {
        Book::factory(33)->create()->each(function ($book) {
            $numReviews = random_int(3, 10);

            Review::factory($numReviews)->good()->for($book)->create();
            Review::factory($numReviews)->average()->for($book)->create();
            Review::factory($numReviews)->bad()->for($book)->create();
        });
    }
}
