<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    public function store(Request $request, Board $board, Column $column)
    {
        $request->validateWithBag('task', [
            'title'         =>  'required|string|max:255',
            'description'   =>  'nullable|string',
            'due_date'      =>  'nullable|date',
        ]);

        $column->tasks()->create($request->only('title', 'description', 'due_date'));

        return redirect()->route('boards.show', $board->id); 
    }

    public function update(Request $request, Board $board, Column $column, Task $task)
    {

        // Can contain previous data from task that needs to be remember if there is a mistake

        $validator = Validator::make($request->all(), [
            'title'         =>  'required|string|max:255',
            'description'   =>  'nullable|string',
            'due_date'      =>  'nullable|date',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator, 'taskUpdate')
                ->withInput()
                ->with([
                    'board_id'  =>  $board->id,
                    'column_id' =>  $column->id,
                    'task_id'   =>  $task->id,
                ]);
        }

        $task->update($request->only('title', 'description', 'due_date'));

        return redirect()->route('boards.show', $board->id);
    }

    public function destroy(Board $board, Column $column, Task $task)
    {
        $task->delete();

        return redirect()->route('boards.show', $board->id);
    }
}
