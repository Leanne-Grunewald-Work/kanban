<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use Illuminate\Http\Request;

class SubtaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Board $board, Column $column, Task $task)
    {

        $request->merge(json_decode($request->getContent(), true));


        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $task->subtasks()->create(['title' => $request->title]);

        return response()->json([
            'subtasks' => $task->subtasks()->get()
        ]);
    }


    /**
     * Display the specified resource.
     */
    public function show(Subtask $subtask)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Subtask $subtask)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board, Column $column, Task $task, Subtask $subtask)
    {
        $request->validateWithBag('subtaskUpdate', [
            'title' => 'required|string|max:255',
        ]);

        $subtask->update([
            'title' => $request->title,
        ]);

        return back();
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board, Column $column, Task $task, Subtask $subtask)
    {
        $subtask->delete();

        return back();
    }

    public function toggle(Request $request, Board $board, Column $column, Task $task, Subtask $subtask)
    {
        // Extra safety: check
        if ($subtask->task_id !== $task->id) {
            return response()->json(['error' => 'Subtask not found for task.'], 404);
        }

        $subtask->update([
            'is_completed' => !$subtask->is_completed,
        ]);

        return response()->json([
            'subtasks' => $task->subtasks()->get()
        ]);
    }




}
