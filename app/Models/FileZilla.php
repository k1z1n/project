<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FileZilla extends Model
{
    use HasFactory;

    protected $fillable = [
        'host',
'username',
'password',
'user_id',
    ];
}
