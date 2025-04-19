<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Column extends Model
{

    protected $fillable = ['title'];

    public function board()
    {
        return $this->belongsTo(Board::class);
    }

    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
