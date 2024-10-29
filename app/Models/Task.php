<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'module_id',
        'user_id'
    ];

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function domain()
    {
        return $this->user()->with('subdomains');  // Поддомены связаны с пользователем
    }
    public function comments(){
        return $this->hasMany(Comment::class);
    }
}
