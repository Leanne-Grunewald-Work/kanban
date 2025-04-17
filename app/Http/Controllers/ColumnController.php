<?php

namespace App\Http\Controllers;

use App\Models\Column;
use App\Http\Controllers\Controller;
use App\Models\Board;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ColumnController extends Controller
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
    public function create(Board $board)
    {
        //$this->authorize('update', $board); // optional policy
        //return view('columns.create', compact('board'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Board $board)
    {
        $request->validateWithBag('column', [
            'title' => 'required|string|max:255',
        ]);

        $board->columns()->create([
            'title' => $request->title,
            'order' => ($board->columns()->max('order') ?? 0) + 1,
        ]);

        return redirect()->route('boards.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Column $column)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Board $board, Column $column)
    {
        //$this->authorize('update', $board);

        //return view('columns.edit', compact('board', 'column'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board, Column $column)
    {
        //$this->authorize('update', $board);

        $request->validateWithBag('columnUpdate', [
            'title' => 'required|string|max:255',
        ]);
    
        $column->update([
            'title' => $request->title,
        ]);
    
        return redirect()->route('boards.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board, Column $column)
    {
        //$this->authorize('delete', $board);

        $column->delete();

        return redirect()->route('boards.index');
    }
}
