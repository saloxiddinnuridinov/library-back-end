<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = Subject::orderByDesc('id')->get();

        return view('subject.subject', ['models' => $models]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('subject.add-subject');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'department_id' => 'required|integer',
			'name' => 'required|string|max:255',
			'tag' => 'required|string|max:255',
			'position' => 'required|string|max:255',
			'description' => 'required|string|max:255'
        ]);

        $subject = new Subject();
        $subject->department_id = $request->department_id;
		$subject->name = $request->name;
		$subject->tag = $request->tag;
		$subject->position = $request->position;
		$subject->description = $request->description;
        $subject->save();

        return redirect()->route('subject.index')->with(['message' => "Subject create successfully"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $subject = Subject::find($id);
        return view('subject.delete-subject', ['id' => $id, 'model' => $subject]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $subject = Subject::find($id);
        if(!$subject){
            abort(404);
        }
        return view('subject.edit-subject', ['model' => $subject]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'department_id' => 'required|integer',
			'name' => 'required|string|max:255',
			'tag' => 'required|string|max:255',
			'position' => 'required|string|max:255',
			'description' => 'required|string|max:255'
        ]);

        $subject = Subject::find($id);
        if (!$subject) {
            abort(404);
        }
        $subject->department_id = $request->department_id;
		$subject->name = $request->name;
		$subject->tag = $request->tag;
		$subject->position = $request->position;
		$subject->description = $request->description;
        $subject->update();

        return redirect()->route('subject.index')->with(['message' => "Subject update successfully"]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $subject = Subject::find($id);
        if(!$subject){
            abort(404);
        }
        
        $subject->delete();

        return redirect()->route('subject.index')->with(['message' => 'Subject delete successfully']);
    }
}
