<?php

namespace App\Http\Controllers;

use App\Models\SubjectJoinBook;
use Illuminate\Http\Request;

class SubjectJoinBookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = SubjectJoinBook::orderByDesc('id')->get();

        return view('subject_join_book.subject_join_book', ['models' => $models]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subject_join_book.add-subject_join_book');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'subject_id' => 'required|integer',
			'book_id' => 'required|integer'
        ]);

        $subject_join_book = new SubjectJoinBook();
        $subject_join_book->subject_id = $request->subject_id;
		$subject_join_book->book_id = $request->book_id;
        $subject_join_book->save();

        return redirect()->route('subject_join_book.index')->with(['message' => "SubjectJoinBook create successfully"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject_join_book = SubjectJoinBook::find($id);
        return view('subject_join_book.delete-subject_join_book', ['id' => $id, 'model' => $subject_join_book]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subject_join_book = SubjectJoinBook::find($id);
        if(!$subject_join_book){
            abort(404);
        }
        return view('subject_join_book.edit-subject_join_book', ['model' => $subject_join_book]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'subject_id' => 'required|integer',
			'book_id' => 'required|integer'
        ]);

        $subject_join_book = SubjectJoinBook::find($id);
        if (!$subject_join_book) {
            abort(404);
        }
        $subject_join_book->subject_id = $request->subject_id;
		$subject_join_book->book_id = $request->book_id;
        $subject_join_book->update();

        return redirect()->route('subject_join_book.index')->with(['message' => "SubjectJoinBook update successfully"]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject_join_book = SubjectJoinBook::find($id);
        if(!$subject_join_book){
            abort(404);
        }
        
        $subject_join_book->delete();

        return redirect()->route('subject_join_book.index')->with(['message' => 'SubjectJoinBook delete successfully']);
    }
}
