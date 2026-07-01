# Mi Librería Virtual

![Laravel](https://img.shields.io/badge/Laravel-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![PHP](https://img.shields.io/badge/PHP-777BB4?style=for-the-badge&logo=php&logoColor=white)
![Blade](https://img.shields.io/badge/Blade-FF2D20?style=for-the-badge&logo=laravel&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-005C84?style=for-the-badge&logo=mysql&logoColor=white)
![Vite](https://img.shields.io/badge/Vite-B73BFE?style=for-the-badge&logo=vite&logoColor=FFD62E)

> Aplicación web Full-Stack desarrollada con Laravel para la gestión de una librería virtual. El proyecto incluye catálogo de libros, carrito de compras, autenticación de usuarios y un flujo completo de compra con generación de facturas.

---

# Descripción

**Mi Librería Virtual** es una aplicación desarrollada como proyecto de aprendizaje para profundizar en el ecosistema Laravel y en el desarrollo de aplicaciones Full-Stack utilizando el patrón MVC.

Además de la gestión de un catálogo de libros, la aplicación implementa un flujo de compra completo con autenticación, carrito basado en sesiones, control de stock y generación de facturas, simulando el funcionamiento de un pequeño comercio electrónico.

El objetivo principal fue consolidar conocimientos sobre arquitectura Laravel, Eloquent ORM, bases de datos relacionales y organización de proyectos siguiendo buenas prácticas de desarrollo.

---

# Funcionalidades

## Gestión del catálogo

- Visualización del catálogo de libros
- Consulta del detalle de cada libro
- Filtrado y búsqueda
- Sección de libros destacados

## Carrito de compras

- Añadir libros al carrito
- Modificar cantidades
- Eliminar productos
- Persistencia del carrito mediante sesiones

## Gestión de usuarios

- Registro de usuarios
- Inicio de sesión
- Protección mediante middleware
- Gestión de datos personales necesarios para la compra

## Proceso de compra

- Checkout completo
- Generación de facturas
- Actualización automática del stock
- Persistencia de pedidos

---

# Stack Tecnológico

## Backend

- PHP
- Laravel
- Eloquent ORM

## Frontend

- Blade Templates
- HTML5
- CSS3
- JavaScript
- Vite

## Base de Datos

- MySQL

## Herramientas

- Composer
- NPM

---

# Arquitectura

El proyecto sigue la arquitectura MVC propuesta por Laravel, manteniendo una clara separación entre la lógica de negocio, el acceso a datos y la interfaz de usuario.

```text
Cliente
   │
   ▼
Routes (web.php)
   │
   ▼
Controllers
   │
   ▼
Models (Eloquent)
   │
   ▼
MySQL
```

La lógica relacionada con el carrito de compra se encuentra desacoplada mediante un servicio específico, favoreciendo un código más limpio y mantenible.

---

# Estructura del Proyecto

```text
app/
 ├── Http/
 │    └── Controllers/
 ├── Models/
 └── Services/

database/
 └── migrations/

resources/
 └── views/

routes/
 └── web.php

public/
```

---

# Instalación

Clona el repositorio:

```bash
git clone https://github.com/Code-Moises/mi-libreria-virtual.git
cd mi-libreria-virtual
```

Instala las dependencias:

```bash
composer install
npm install
```

Crea el archivo de configuración:

```bash
cp .env.example .env
```

Genera la clave de la aplicación:

```bash
php artisan key:generate
```

Configura la conexión a la base de datos dentro del archivo `.env`.

Ejecuta las migraciones:

```bash
php artisan migrate
```

Inicia el servidor:

```bash
php artisan serve
```

Compila los recursos:

```bash
npm run dev
```

La aplicación estará disponible en:

```text
http://localhost:8000
```

---

# Conceptos Practicados

Durante el desarrollo del proyecto se trabajó con:

- Arquitectura MVC
- Laravel Routing
- Controladores
- Eloquent ORM
- Migraciones
- Relaciones entre modelos
- CRUD completo
- Blade Templates
- Formularios y validación
- Middleware de autenticación
- Gestión de sesiones
- Carrito de compras
- Flujo de checkout
- Organización de proyectos Laravel

---

# Autor

**Moisés Becerra**

Full-Stack Developer

Portfolio: https://moisesbecerra.dev

LinkedIn: https://www.linkedin.com/in/moises-becerra-morales/

GitHub: https://github.com/Code-Moises

---

## Licencia

Este proyecto ha sido desarrollado con fines educativos y como práctica de desarrollo Full-Stack utilizando Laravel.
