<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Akun extends Authenticatable
{
    protected $table = 'akun';
    protected $primaryKey = 'id_akun';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'username',
        'email',
        'password',
        'level',
    ];

    protected $hidden = [
        'password',
    ];
}