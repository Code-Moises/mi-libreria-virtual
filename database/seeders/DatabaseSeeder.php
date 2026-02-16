<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //crea 50 libros falsos
        Book::factory(50)->create();

       /*User::factory(10)->create();
         User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
         ]);*/
    }
}
