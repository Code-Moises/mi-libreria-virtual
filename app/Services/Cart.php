<?php

namespace App\Services;

use App\Models\Book;
use ArrayIterator;
use Countable;
use IteratorAggregate;
use Traversable;

/**
 * Servicio Cart (Carrito de Compras).
 * Esta clase NO es un Modelo de Eloquent (no interactúa con la base de datos).
 * Es una clase PHP pura (Service) que se encarga de aislar toda la lógica de negocio
 * del carrito (añadir, quitar, sumar precios) para que los Controladores queden limpios.
 * - Implementa 'IteratorAggregate': Permite que usemos esta clase directamente en un foreach ($cart as $item).
 * - Implementa 'Countable': Permite que podamos usar la función count() directamente sobre el objeto: count($cart).
 */
class Cart implements IteratorAggregate, Countable
{
    /**
     * En este sistema, el array guarda CADA UNIDAD como un elemento separado.
     * Si compras 3 unidades de "Harry Potter", el array será: [HarryPotter, HarryPotter, HarryPotter].
     * No guarda un array asociativo con cantidades. Se agrupan después en la vista o controlador.
     */
    private array $items = [];

    /**
     * Añade un libro al array interno.
     */
    public function add(Book $book): void
    {
        $this->items[] = $book; // Es el equivalente a array_push()
    }

    /**
     * Método obligatorio por la interfaz IteratorAggregate.
     * Le dice a PHP cómo debe recorrer este objeto si alguien hace un "foreach ($cart as $item)".
     */
    public function getIterator(): Traversable
    {
        // Envuelve el array interno en una clase que PHP sabe cómo iterar
        return new ArrayIterator($this->items);
    }

    /**
     * Método obligatorio por la interfaz Countable.
     * Le dice a PHP qué debe devolver si alguien hace un "count($cart)".
     */
    public function count(): int
    {
        return count($this->items);
    }

    /**
     * Devuelve el array completo de libros, tal cual.
     */
    public function getBooks(): array
    {
        return $this->items;
    }

    /**
     * Elimina TODAS las copias de un libro específico
     */
    public function delete(Book $book): void
    {
        // 1. array_filter recorre el array. Si devolvemos 'true', se queda. Si 'false', se elimina.
        // Quitamos todos los libros cuyo ID sea exactamente igual al que queremos borrar.
        $this->items = array_filter($this->items, function($item) use ($book) {
            return $item->id !== $book->id;
        });

        // 2. Reindexar el array.
        // Si teníamos las posiciones [0, 1, 2] y borramos el 1, PHP deja el array como [0 => A, 2 => C].
        // Ese "hueco" en los índices puede romper bucles for() o dar problemas al guardar en la sesión.
        // array_values() lo arregla y lo vuelve a dejar ordenado: [0, 1].
        $this->items = array_values($this->items);
    }

    /**
     * Cuenta cuántas copias de un MISMO libro hay en el carrito actualmente.
     */
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

    /**
     * Elimina SOLO UNA copia del libro (Para el botón "➖ Restar cantidad").
     */
    public function removeOne(Book $book): void
    {
        foreach ($this->items as $key => $item) {
            // Buscamos la primera coincidencia
            if ($item->id === $book->id) {
                unset($this->items[$key]); // La eliminamos
                $this->items = array_values($this->items); // Reindexamos para evitar huecos (explicado arriba)

                // Un `return` corta en seco el foreach y la función entera.
                // Como solo queríamos borrar UNO, en cuanto lo borramos, nos salimos.
                return;
            }
        }
    }

    /**
     * Comprueba de forma rápida si un libro existe en el carrito (Para validaciones iniciales).
     */
    public function has(Book $book): bool
    {
        foreach ($this->items as $item) {
            if ($item->id === $book->id) {
                return true; // Si lo encuentra una sola vez, corta el bucle y devuelve true
            }
        }
        return false;
    }

    /**
     * Vacía el carrito por completo dejándolo como un array nuevo.
     */
    public function clear(): void
    {
        $this->items = [];
    }

    /**
     * Calcula el precio total sumando el Precio de Venta al Público (PVP)
     * de TODOS los libros individuales dentro del array.
     */
    public function getTotal(): float
    {
        $total = 0;
        foreach ($this->items as $item) {
            // Como guardamos [LibroA, LibroA], pasará dos veces por aquí sumando su PVP
            $total += $item->pvp;
        }
        return $total;
    }
}
