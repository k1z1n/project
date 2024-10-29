<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'logo',
        'slug'
    ];

    public function modules(){
        return $this->hasMany(Module::class);
    }

    public function requests()
    {
        return $this->hasMany(Request::class);
    }

}
