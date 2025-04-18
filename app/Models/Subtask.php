<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subtask extends Model
{
    protected $fillable = [
        'title',
        'is_completed',
        'task_id',
    ];

    public function task()
    {
        return $this->belongsTo(Task::class);
    }
}
