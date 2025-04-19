<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'due_date',
    ];

    public function column()
    {
        return $this->belongsTo(Column::class);
    }

    public function subtasks()
    {
        return $this->hasMany(Subtask::class);
    }
}
