<?php

namespace App\Http\Controllers;

use App\Models\RentBook;
use Illuminate\Http\Request;

class RentBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = RentBook::orderByDesc('id')->get();

        return view('rent_book.rent_book', ['models' => $models]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('rent_book.add-rent_book');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'user_id' => 'required|integer',
			'book_id' => 'required|integer',
			'star_time' => 'required|date',
			'end_time' => 'required|date',
			'is_taken' => 'required|boolean'
        ]);

        $rent_book = new RentBook();
        $rent_book->user_id = $request->user_id;
		$rent_book->book_id = $request->book_id;
		$rent_book->star_time = $request->star_time;
		$rent_book->end_time = $request->end_time;
		$rent_book->is_taken = $request->is_taken;
        $rent_book->save();

        return redirect()->route('rent_book.index')->with(['message' => "RentBook create successfully"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $rent_book = RentBook::find($id);
        return view('rent_book.delete-rent_book', ['id' => $id, 'model' => $rent_book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $rent_book = RentBook::find($id);
        if(!$rent_book){
            abort(404);
        }
        return view('rent_book.edit-rent_book', ['model' => $rent_book]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'user_id' => 'required|integer',
			'book_id' => 'required|integer',
			'star_time' => 'required|date',
			'end_time' => 'required|date',
			'is_taken' => 'required|boolean'
        ]);

        $rent_book = RentBook::find($id);
        if (!$rent_book) {
            abort(404);
        }
        $rent_book->user_id = $request->user_id;
		$rent_book->book_id = $request->book_id;
		$rent_book->star_time = $request->star_time;
		$rent_book->end_time = $request->end_time;
		$rent_book->is_taken = $request->is_taken;
        $rent_book->update();

        return redirect()->route('rent_book.index')->with(['message' => "RentBook update successfully"]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $rent_book = RentBook::find($id);
        if(!$rent_book){
            abort(404);
        }
        
        $rent_book->delete();

        return redirect()->route('rent_book.index')->with(['message' => 'RentBook delete successfully']);
    }
}
