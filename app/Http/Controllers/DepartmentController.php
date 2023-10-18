<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $models = Department::orderByDesc('id')->get();

        return view('department.department', ['models' => $models]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('department.add-department');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'name' => 'required|string|max:255',
			'image' => 'required|',
			'description' => 'required|string|max:255',
			'phone' => 'required|string|max:255'
        ]);

        $department = new Department();
        $department->name = $request->name;
		if ($request->hasFile("image")) {
            $file = $request->file("image");
            $filename = time(). "_" . $file->getClientOriginalName();
            if ($department->image) {
                $oldFilePath = 'uploads/image/'.basename($department->image);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $file->move("uploads/image", $filename);
            $department->image = asset("uploads/image/$filename");
        }
		$department->description = $request->description;
		$department->phone = $request->phone;
        $department->save();

        return redirect()->route('department.index')->with(['message' => "Department create successfully"]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $department = Department::find($id);
        return view('department.delete-department', ['id' => $id, 'model' => $department]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $department = Department::find($id);
        if(!$department){
            abort(404);
        }
        return view('department.edit-department', ['model' => $department]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
			'image' => 'required|',
			'description' => 'required|string|max:255',
			'phone' => 'required|string|max:255'
        ]);

        $department = Department::find($id);
        if (!$department) {
            abort(404);
        }
        $department->name = $request->name;
		if ($request->hasFile("image")) {
            $file = $request->file("image");
            $filename = time(). "_" . $file->getClientOriginalName();
            if ($department->image) {
                $oldFilePath = 'uploads/image/'.basename($department->image);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $file->move("uploads/image", $filename);
            $department->image = asset("uploads/image/$filename");
        }
		$department->description = $request->description;
		$department->phone = $request->phone;
        $department->update();

        return redirect()->route('department.index')->with(['message' => "Department update successfully"]);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $department = Department::find($id);
        if(!$department){
            abort(404);
        }
        if ($department->image) {
            $oldFilePath = 'uploads/image/'.basename($department->image);
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }
        $department->delete();

        return redirect()->route('department.index')->with(['message' => 'Department delete successfully']);
    }
}
