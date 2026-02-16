<?php

namespace App\Models;

use IteratorAggregate;
use ArrayIterator;
use Traversable;
use Countable;
use App\Models\Book; // Importamos el modelo Book de Laravel

class Cart implements IteratorAggregate, Countable
{
    private array $items = [];

    // Añade un libro al array
    public function add(Book $book): void
    {
        $this->items[] = $book;
    }

    // Permite recorrer el objeto con foreach
    public function getIterator(): Traversable
    {
        return new ArrayIterator($this->items);
    }

    // Cuenta el total de items (libros individuales)
    public function count(): int
    {
        return count($this->items);
    }

    // Devuelve el array completo
    public function getBooks(): array
    {
        return $this->items;
    }

    // Elimina todas las copias de un libro específico
    public function delete(Book $book): void
    {
        // Filtramos para quitar los que tengan el mismo ID
        $this->items = array_filter($this->items, function($item) use ($book) {
            return $item->id !== $book->id;
        });

        // Reindexar para evitar huecos en el array
        $this->items = array_values($this->items);
    }

    // Cuenta cuántas copias de un libro hay en el carrito
    public function countBook(Book $book): int
    {
        $count = 0;
        foreach ($this->items as $item) {
            if ($item->id === $book->id) {
                $count++;
            }
        }
        return $count;
    }

    // Elimina SOLO UNA copia del libro (para el botón "-")
    public function removeOne(Book $book): void
    {
        foreach ($this->items as $key => $item) {
            if ($item->id === $book->id) {
                unset($this->items[$key]);
                $this->items = array_values($this->items);
                return; // Salimos tras borrar uno solo
            }
        }
    }

    // Comprueba si el libro existe en el carrito
    public function has(Book $book): bool
    {
        foreach ($this->items as $item) {
            if ($item->id === $book->id) {
                return true;
            }
        }
        return false;
    }

    // Vacía el carrito
    public function clear(): void
    {
        $this->items = [];
    }

    // [NUEVO] Método auxiliar para calcular el precio total de todo el carrito
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            $total += $item->pvp;
        }
        return $total;
    }
}
