<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Query extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'query', 'last_access', 'server_id',
    ];

    public function server()
    {
        return $this->belongsTo(Server::class);
    }
}
