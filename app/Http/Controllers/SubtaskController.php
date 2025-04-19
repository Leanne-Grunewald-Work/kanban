<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use App\Models\Subtask;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    public function store(Request $request, Board $board, Column $column, Task $task)
    {
        $data = $request->merge(json_decode($request->getContent(), true))->validate([
            'title' => 'required|string|max:255',
        ]);

        $task->subtasks()->create(['title' => $data['title']]);

        return response()->json([
            'subtasks' => $task->subtasks()->get(),
        ]);
    }

    public function update(Request $request, Board $board, Column $column, Task $task, Subtask $subtask)
    {
        $data = $request->merge(json_decode($request->getContent(), true))->validate([
            'title' => 'required|string|max:255',
        ]);

        $subtask->update(['title' => $data['title']]);

        return response()->json([
            'subtasks' => $task->subtasks()->get(),
        ]);
    }

    public function destroy(Board $board, Column $column, Task $task, Subtask $subtask)
    {
        if ($subtask->task_id !== $task->id) {
            return response()->json(['error' => 'Subtask not found for task.'], 404);
        }

        $subtask->delete();

        return response()->json([
            'subtasks' => $task->subtasks()->get(),
        ]);
    }

    public function toggle(Board $board, Column $column, Task $task, Subtask $subtask)
    {
        if ($subtask->task_id !== $task->id) {
            return response()->json(['error' => 'Subtask not found for task.'], 404);
        }

        $subtask->update([
            'is_completed' => !$subtask->is_completed,
        ]);

        return response()->json([
            'subtasks' => $task->subtasks()->get(),
        ]);
    }

    public function list(Board $board, Column $column, Task $task)
    {
        return response()->json([
            'subtasks' => $task->subtasks()->get(),
        ]);
    }
}
