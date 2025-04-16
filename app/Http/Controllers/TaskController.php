<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Http\Controllers\Controller;
use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\Request;

class TaskController extends Controller
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
    public function create(Board $board, Column $column)
    {
        //$this->authorize('update', $board); // optional policy
        return view('tasks.create', compact('board', 'column'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Board $board, Column $column)
    {
        //$this->authorize('update', $board);

        $request->validate([
            'title'         =>  'required|string|max:255',
            'description'   =>  'nullable|string',
            'due_date'      =>  'nullable|date',
        ]);

        $column->tasks()->create([
            'title'         =>  $request->title,
            'description'   =>  $request->description,
            'due_date'      =>  $request->due_date,
        ]);

        return redirect()->route('boards.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Board $board, Column $column, Task $task)
    {
        return view('tasks.show', compact('board', 'column', 'task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Board $board, Column $column, Task $task)
    {
        //$this->authorize('update', $board);

        return view('tasks.edit', compact('board', 'column', 'task'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board, Column $column, Task $task)
    {
        //$this->authorize('update', $board);

        $request->validate([
            'title'         =>  'required|string|max:255',
            'description'   =>  'nullable|string',
            'due_date'      =>  'nullable|date',
        ]);

        $task->update($request->only('title', 'description', 'due_date'));

        return redirect()->route('boards.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board, Column $column, Task $task)
    {
        //$this->authorize('delete', $board);

        $task->delete();

        return redirect()->route('boards.index');
    }
}
