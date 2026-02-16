<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        // Generamos un número aleatorio para que cada libro tenga una foto distinta
        // y el navegador no cachee la misma imagen para todos.
        $randomNumber = fake()->numberBetween(1, 1000);

        return [
            'isbn' => fake()->isbn13(),
            'title' => fake()->sentence(3), //titulo de 3 palabras
            'author' => fake()->name(),
            'pvp' => fake()->randomFloat(2, 10, 50), //precios entre 10 y 50
            'iva' => 0.21,
            'stock' => fake()->numberBetween(0, 100),
            'description' => fake()->paragraph(),
            'editorial' => fake()->company(),
            'image' => "https://picsum.photos/seed/{$randomNumber}/200/300",
            'sales_count' => fake()->numberBetween(0, 500), //para el Top Ventas
        ];
    }
}
