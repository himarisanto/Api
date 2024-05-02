<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Server extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_koneksi',
        'driver',
        'host',
        'port',
        'username',
        'password',
        'note',
    ];
}
