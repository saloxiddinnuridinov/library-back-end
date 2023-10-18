<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Http\Resources\SubjectResource;
use App\Http\ValidatorResponse;
use Illuminate\Http\Request;

  /**
 * @OA\Schema(
 *     schema="Subject",
 *     title="Subject title",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Successfully"),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *              @OA\Property(property="id", type="integer"),
 *				@OA\Property(property="department_id", type="integer"),
 *				@OA\Property(property="name", type="string"),
 *				@OA\Property(property="tag", type="string"),
 *				@OA\Property(property="position", type="string"),
 *				@OA\Property(property="description", type="string"),
 *				@OA\Property(property="created_at", type="string", format="date-time"),
 *				@OA\Property(property="updated_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Property(property="code", type="integer", example=200),
 * )
 */

class SubjectController extends Controller
{
    /**
 *@OA\Get(
 *      path="/api/v1/subject",
 *      security={{"api":{}}},
 *      operationId="subject_index",
 *      summary="Get all Subjects",
 *      description="Retrieve all Subjects",
 *      tags={"Subject API CRUD"},
 *      @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Subject"),
 *      ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function index()
    {
        $subjectS = Subject::all();
        return response()->json([
            'data' => SubjectResource::collection($subjectS),
        ]);
    }

    /**
 * @OA\Get(
 *      path="/api/v1/subject/{id}",
 *      security={{"api":{}}},
 *      operationId="subject_show",
 *      summary="Get a single Subject by ID",
 *      description="Retrieve a single Subject by its ID",
 *      tags={"Subject API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the Subject to retrieve",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(response=200,description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Subject"),
 *     ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function show(string $id)
    {
        $subject = Subject::find($id);
        if(!$subject){
            return response()->json([
                'message' => "Subject not found",
                'code' => 403,
            ]);
        }

        return response()->json([
            'data' => new SubjectResource($subject),
            'code' => 200
        ]);
    }

    /**
 * @OA\Post(
 *      path="/api/v1/subject",
 *      security={{"api":{}}},
 *      operationId="subject_store",
 *      summary="Create a new Subject",
 *      description="Add a new Subject",
 *      tags={"Subject API CRUD"},
 *      @OA\RequestBody(required=true, description="Subject save",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required={"department_id", "name", "tag", "position", "description"},
 *                  @OA\Property(property="department_id", type="integer", example=""),
 *					@OA\Property(property="name", type="string", example=""),
 *					@OA\Property(property="tag", type="string", example=""),
 *					@OA\Property(property="position", type="string", example=""),
 *					@OA\Property(property="description", type="string", example="")
 *              )
 *          )
 *      ),
 *       @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Subject"),
 *      ),
 *       @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function store(Request $request)
    {
        $rules = array (
  'department_id' => 'required|integer',
  'name' => 'required|string|max:255',
  'tag' => 'required|string|max:255',
  'position' => 'required|string|max:255',
  'description' => 'required|string|max:255',
);
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if($validator->fails){
            return response()->json([
                'message' => $validator->response,
                'code' => 400
            ]);
        }
        $subject = new Subject();
        $subject->department_id = $request->department_id;
		$subject->name = $request->name;
		$subject->tag = $request->tag;
		$subject->position = $request->position;
		$subject->description = $request->description;
        $subject->save();
        return response()->json([
            'data' => new SubjectResource($subject),
            'code' => 200
        ]);
    }

    /**
 * @OA\Post(
 *      path="/api/v1/subject/{id}",
 *      security={{"api":{}}},
 *      operationId="subject_update",
 *      summary="Update a Subject by ID",
 *      description="Update a specific Subject by its ID",
 *      tags={"Subject API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the Subject to update",
 *          @OA\Schema(type="integer")
 *      ),
 *           @OA\RequestBody(required=true, description="Subject save",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required={"department_id", "name", "tag", "position", "description"},
 *                  @OA\Property(property="department_id", type="integer", example=""),
 *					@OA\Property(property="name", type="string", example=""),
 *					@OA\Property(property="tag", type="string", example=""),
 *					@OA\Property(property="position", type="string", example=""),
 *					@OA\Property(property="description", type="string", example=""),
 *				    @OA\Property(property="_method", type="string", example="PUT", description="Read-only: This property cannot be modified."),
 *              )
 *          )
 *      ),
 *
 *     @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Subject"),
 *      ),
 *     @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function update(Request $request, string $id)
    {
        $rules = array (
  'department_id' => 'required|integer',
  'name' => 'required|string|max:255',
  'tag' => 'required|string|max:255',
  'position' => 'required|string|max:255',
  'description' => 'required|string|max:255',
);
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if($validator->fails){
            return response()->json([
                'message' => $validator->response,
                'code' => 400
            ]);
        }
        $subject = Subject::find($id);
        if(!$subject){
            return response()->json([
                'message' => "Event not found",
                'code' => 404
            ]);
        }
        $subject->department_id = $request->department_id;
		$subject->name = $request->name;
		$subject->tag = $request->tag;
		$subject->position = $request->position;
		$subject->description = $request->description;
        return response()->json([
            'data' => new SubjectResource($subject),
            'code' => 200
        ]);
    }

    /**
 * @OA\Delete(
 *      path="/api/v1/subject/{id}",
 *      security={{"api":{}}},
 *      operationId="subject_delete",
 *      summary="Delete a Subject by ID",
 *      description="Remove a specific Subject by its ID",
 *      tags={"Subject API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the Subject to delete",
 *          @OA\Schema(type="integer")
 *      ),
 *          @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Subject"),
 *      ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function destroy(string $id)
    {
        $subject = Subject::find($id);
        if(!$subject){
            return response()->json([
                'message' => "Subject not found",
                'code' => 404,
            ]);
        }
        $subject->delete();
        return response()->json([
            'data' => new SubjectResource($subject),
            'code' => 200
        ]);
    }
}
