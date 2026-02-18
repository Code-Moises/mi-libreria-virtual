<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    // HasFactory: Permite usar User::factory() para crear usuarios falsos en los Seeders.
    // Notifiable: Permite enviar notificaciones a este usuario (ej: $user->notify(new InvoicePaid())).
    use HasFactory, Notifiable;

    /**
     * $fillable - Campos permitidos para la Asignación Masiva (Mass Assignment).
     * Solo los campos listados aquí podrán ser rellenados automáticamente al hacer:
     * User::create($request->all());
     * Protege la base de datos de campos inyectados maliciosamente (ej: 'is_admin').
     */
    protected $fillable = [
        'dni',
        'name',
        'lastname',
        'username',
        'email',
        'addrs',
        'password',
    ];

    //Ocultar contraseña al convertir el usuario a JSON/Array
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * casts() - Mutadores y conversión de tipos de datos.
     * * Le dice a Laravel cómo debe tratar ciertos campos al leerlos o escribirlos en la BD.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
