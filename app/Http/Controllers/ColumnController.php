<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Models\Column;
use Illuminate\Http\Request;

class ColumnController extends Controller
{
    /**
     * Store a newly created column.
     */
    public function store(Request $request, Board $board)
    {
        $request->validateWithBag('columnCreate', [
            'title' => 'required|string|max:255',
        ]);

        $maxOrder = $board->columns()->max('order') ?? 0;

        $board->columns()->create([
            'title' => $request->title,
            'order' => $maxOrder + 1,
        ]);

        //return redirect()->route('boards.show', $board);
        return back();
    }

    /**
     * Update the specified column.
     */
    public function update(Request $request, Board $board, Column $column)
    {
        $request->validateWithBag('columnUpdate', [
            'title' => 'required|string|max:255',
        ]);

        $column->update([
            'title' => $request->title,
        ]);

        //return redirect()->route('boards.show', $board);
        return back();
    }

    /**
     * Remove the specified column.
     */
    public function destroy(Board $board, Column $column)
    {
        $column->delete();

        //return redirect()->route('boards.show', $board);
        return back();
    }
}
