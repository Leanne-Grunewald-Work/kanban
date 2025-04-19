<?php

namespace App\Http\Controllers;

use App\Models\Board;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    /**
     * Display a listing of the user's boards.
     */
    public function index()
    {
        $boards = Board::where('user_id', auth()->id())->get();

        if ($boards->isNotEmpty()) {
            return redirect()->route('boards.show', $boards->first()->id);
        }

        return view('boards.index', compact('boards'));
    }

    /**
     * Store a newly created board in storage.
     */
    public function store(Request $request)
    {
        $request->validateWithBag('board', [
            'title' => 'required|string|max:255',
        ]);

        auth()->user()->boards()->create([
            'title' => $request->title,
        ]);

        return redirect()->route('boards.index');
    }

    /**
     * Display the specified board along with its columns, tasks, and subtasks.
     */
    public function show(Board $board)
    {
        $boards = Board::where('user_id', auth()->id())->get(); // For sidebar navigation
        $board->load('columns.tasks.subtasks'); // Load columns, tasks, subtasks in order to not lazy load (n+1 query)

        return view('boards.index', compact('boards', 'board'));
    }

    /**
     * Update the specified board.
     */
    public function update(Request $request, Board $board)
    {
        $request->validateWithBag('boardUpdate', [
            'title' => 'required|string|max:255',
        ]);

        $board->update([
            'title' => $request->title,
        ]);

        return redirect()->route('boards.index');
    }

    /**
     * Remove the specified board.
     */
    public function destroy(Board $board)
    {
        $board->delete();

        return redirect()->route('boards.index');
    }
}
