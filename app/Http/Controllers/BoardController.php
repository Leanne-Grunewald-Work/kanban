<?php

namespace App\Http\Controllers;

use App\Models\Board;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BoardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $boards = auth()->user()->boards()->with('columns.tasks')->get();
        return view('boards.index', compact('boards'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //return view('boards.create');
    }

    /**
     * Store a newly created resource in storage.
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
     * Display the specified resource.
     */
    public function show(Board $board)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Board $board)
    {
        //$this->authorize('update', $board); // Optional: Add policies

        //return view('boards.edit', compact('board'));


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Board $board)
    {

        //$this->authorize('update', $board); // Optional: Add policies

        $request->validateWithBag('boardUpdate', [
            'title' => 'required|string|max:255',
        ]);
    
        $board->update([
            'title' => $request->title,
        ]);
    
        return redirect()->route('boards.index');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Board $board)
    {
        //$this->authorize('delete', $board);

        $board->delete();

        return redirect()->route('boards.index');
    }
}
