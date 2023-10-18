<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Http\Resources\DepartmentResource;
use App\Http\ValidatorResponse;
use Illuminate\Http\Request;

  /**
 * @OA\Schema(
 *     schema="Department",
 *     title="Department title",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Successfully"),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *              @OA\Property(property="id", type="integer"),
 *				@OA\Property(property="name", type="string"),
 *				@OA\Property(property="image", type="file"),
 *				@OA\Property(property="description", type="string"),
 *				@OA\Property(property="phone", type="string"),
 *				@OA\Property(property="created_at", type="string", format="date-time"),
 *				@OA\Property(property="updated_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Property(property="code", type="integer", example=200),
 * )
 */

class DepartmentController extends Controller
{
    /**
 *@OA\Get(
 *      path="/api/v1/department",
 *      security={{"api":{}}},
 *      operationId="department_index",
 *      summary="Get all Departments",
 *      description="Retrieve all Departments",
 *      tags={"Department API CRUD"},
 *      @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Department"),
 *      ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function index()
    {
        $departmentS = Department::all();
        return response()->json([
            'data' => DepartmentResource::collection($departmentS),
        ]);
    }

    /**
 * @OA\Get(
 *      path="/api/v1/department/{id}",
 *      security={{"api":{}}},
 *      operationId="department_show",
 *      summary="Get a single Department by ID",
 *      description="Retrieve a single Department by its ID",
 *      tags={"Department API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the Department to retrieve",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(response=200,description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Department"),
 *     ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function show(string $id)
    {
        $department = Department::find($id);
        if(!$department){
            return response()->json([
                'message' => "Department not found",
                'code' => 403,
            ]);
        }

        return response()->json([
            'data' => new DepartmentResource($department),
            'code' => 200
        ]);
    }

    /**
 * @OA\Post(
 *      path="/api/v1/department",
 *      security={{"api":{}}},
 *      operationId="department_store",
 *      summary="Create a new Department",
 *      description="Add a new Department",
 *      tags={"Department API CRUD"},
 *      @OA\RequestBody(required=true, description="Department save",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required={"name", "image", "description", "phone"},
 *                  @OA\Property(property="name", type="string", example=""),
 *					@OA\Property(property="image", type="file", example=""),
 *					@OA\Property(property="description", type="string", example=""),
 *					@OA\Property(property="phone", type="string", example="")
 *              )
 *          )
 *      ),
 *       @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Department"),
 *      ),
 *       @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function store(Request $request)
    {
        $rules = array (
  'name' => 'required|string|max:255',
  'image' => 'required|mimes:jpg,ping',
  'description' => 'required|string|max:255',
  'phone' => 'required|string|max:255',
);
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if($validator->fails){
            return response()->json([
                'message' => $validator->response,
                'code' => 400
            ]);
        }
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
        return response()->json([
            'data' => new DepartmentResource($department),
            'code' => 200
        ]);
    }

    /**
 * @OA\Post(
 *      path="/api/v1/department/{id}",
 *      security={{"api":{}}},
 *      operationId="department_update",
 *      summary="Update a Department by ID",
 *      description="Update a specific Department by its ID",
 *      tags={"Department API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the Department to update",
 *          @OA\Schema(type="integer")
 *      ),
 *           @OA\RequestBody(required=true, description="Department save",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required={"name", "image", "description", "phone"},
 *                  @OA\Property(property="name", type="string", example=""),
 *					@OA\Property(property="image", type="file", example=""),
 *					@OA\Property(property="description", type="string", example=""),
 *					@OA\Property(property="phone", type="string", example=""),
 *				    @OA\Property(property="_method", type="string", example="PUT", description="Read-only: This property cannot be modified."),
 *              )
 *          )
 *      ),
 *
 *     @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Department"),
 *      ),
 *     @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function update(Request $request, string $id)
    {
        $rules = array (
  'name' => 'required|string|max:255',
  'image' => 'required|mimes:',
  'description' => 'required|string|max:255',
  'phone' => 'required|string|max:255',
);
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if($validator->fails){
            return response()->json([
                'message' => $validator->response,
                'code' => 400
            ]);
        }
        $department = Department::find($id);
        if(!$department){
            return response()->json([
                'message' => "Event not found",
                'code' => 404
            ]);
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
        return response()->json([
            'data' => new DepartmentResource($department),
            'code' => 200
        ]);
    }

    /**
 * @OA\Delete(
 *      path="/api/v1/department/{id}",
 *      security={{"api":{}}},
 *      operationId="department_delete",
 *      summary="Delete a Department by ID",
 *      description="Remove a specific Department by its ID",
 *      tags={"Department API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the Department to delete",
 *          @OA\Schema(type="integer")
 *      ),
 *          @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Department"),
 *      ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function destroy(string $id)
    {
        $department = Department::find($id);
        if(!$department){
            return response()->json([
                'message' => "Department not found",
                'code' => 404,
            ]);
        }
        $department->delete();
        return response()->json([
            'data' => new DepartmentResource($department),
            'code' => 200
        ]);
    }
}
