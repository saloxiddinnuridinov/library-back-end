<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = Book::orderByDesc('id')->get();

        return view('book.book', ['models' => $models]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('book.add-book');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:255',
			'status' => 'required|string|max:255'
        ]);

        $book = new Book();
        $book->name = $request->name;
		$book->status = $request->status;
		$book->description = $request->description;
        $book->save();

        return redirect()->route('book.index')->with(['message' => "Book create successfully"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $book = Book::find($id);
        return view('book.delete-book', ['id' => $id, 'model' => $book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $book = Book::find($id);
        if(!$book){
            abort(404);
        }
        return view('book.edit-book', ['model' => $book]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
			'status' => 'required|string|max:255'
        ]);

        $book = Book::find($id);
        if (!$book) {
            abort(404);
        }
        $book->name = $request->name;
		$book->status = $request->status;
		$book->description = $request->description;
        $book->update();

        return redirect()->route('book.index')->with(['message' => "Book update successfully"]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $book = Book::find($id);
        if(!$book){
            abort(404);
        }
        
        $book->delete();

        return redirect()->route('book.index')->with(['message' => 'Book delete successfully']);
    }
}
