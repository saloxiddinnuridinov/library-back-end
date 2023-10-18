<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Resources\UserResource;
use App\Http\ValidatorResponse;
use Illuminate\Http\Request;

  /**
 * @OA\Schema(
 *     schema="User",
 *     title="User title",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Successfully"),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *              @OA\Property(property="id", type="integer"),
 *				@OA\Property(property="name", type="string"),
 *				@OA\Property(property="surname", type="string"),
 *				@OA\Property(property="email", type="string"),
 *				@OA\Property(property="password", type="string"),
 *				@OA\Property(property="image", type="file"),
 *				@OA\Property(property="qr_code", type="string"),
 *				@OA\Property(property="group", type="string"),
 *				@OA\Property(property="role", type="string"),
 *				@OA\Property(property="created_at", type="string", format="date-time"),
 *				@OA\Property(property="updated_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Property(property="code", type="integer", example=200),
 * )
 */

class UserController extends Controller
{
    /**
 *@OA\Get(
 *      path="/api/v1/user",
 *      security={{"api":{}}},
 *      operationId="user_index",
 *      summary="Get all Users",
 *      description="Retrieve all Users",
 *      tags={"User API CRUD"},
 *      @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/User"),
 *      ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function index()
    {
        $userS = User::all();
        return response()->json([
            'data' => UserResource::collection($userS),
        ]);
    }

    /**
 * @OA\Get(
 *      path="/api/v1/user/{id}",
 *      security={{"api":{}}},
 *      operationId="user_show",
 *      summary="Get a single User by ID",
 *      description="Retrieve a single User by its ID",
 *      tags={"User API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the User to retrieve",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(response=200,description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/User"),
 *     ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function show(string $id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message' => "User not found",
                'code' => 403,
            ]);
        }

        return response()->json([
            'data' => new UserResource($user),
            'code' => 200
        ]);
    }

    /**
 * @OA\Post(
 *      path="/api/v1/user",
 *      security={{"api":{}}},
 *      operationId="user_store",
 *      summary="Create a new User",
 *      description="Add a new User",
 *      tags={"User API CRUD"},
 *      @OA\RequestBody(required=true, description="User save",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required={"name", "surname", "email", "password", "image", "group", "role"},
 *                  @OA\Property(property="name", type="string", example=""),
 *					@OA\Property(property="surname", type="string", example=""),
 *					@OA\Property(property="email", type="string", example=""),
 *					@OA\Property(property="password", type="string", example=""),
 *					@OA\Property(property="image", type="file", example=""),
 *					@OA\Property(property="qr_code", type="string", example=""),
 *					@OA\Property(property="group", type="string", example=""),
 *					@OA\Property(property="role", type="string", example="")
 *              )
 *          )
 *      ),
 *       @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/User"),
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
  'surname' => 'required|string|max:255',
  'email' => 'required|string|max:255',
  'password' => 'required|string|max:255',
  'image' => 'required|mimes:',
  'group' => 'required|string|max:255',
  'role' => 'required|string|max:255',
);
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if($validator->fails){
            return response()->json([
                'message' => $validator->response,
                'code' => 400
            ]);
        }
        $user = new User();
        $user->name = $request->name;
		$user->surname = $request->surname;
		$user->email = $request->email;
		$user->password = $request->password;
		if ($request->hasFile("image")) {
            $file = $request->file("image");
            $filename = time(). "_" . $file->getClientOriginalName();
            if ($user->image) {
                $oldFilePath = 'uploads/image/'.basename($user->image);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $file->move("uploads/image", $filename);
            $user->image = asset("uploads/image/$filename");
        }
		$user->qr_code = $request->qr_code;
		$user->group = $request->group;
		$user->role = $request->role;
        $user->save();
        return response()->json([
            'data' => new UserResource($user),
            'code' => 200
        ]);
    }

    /**
 * @OA\Post(
 *      path="/api/v1/user/{id}",
 *      security={{"api":{}}},
 *      operationId="user_update",
 *      summary="Update a User by ID",
 *      description="Update a specific User by its ID",
 *      tags={"User API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the User to update",
 *          @OA\Schema(type="integer")
 *      ),
 *           @OA\RequestBody(required=true, description="User save",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required={"name", "surname", "email", "password", "image", "group", "role"},
 *                  @OA\Property(property="name", type="string", example=""),
 *					@OA\Property(property="surname", type="string", example=""),
 *					@OA\Property(property="email", type="string", example=""),
 *					@OA\Property(property="password", type="string", example=""),
 *					@OA\Property(property="image", type="file", example=""),
 *					@OA\Property(property="qr_code", type="string", example=""),
 *					@OA\Property(property="group", type="string", example=""),
 *					@OA\Property(property="role", type="string", example=""),
 *				    @OA\Property(property="_method", type="string", example="PUT", description="Read-only: This property cannot be modified."),
 *              )
 *          )
 *      ),
 *
 *     @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/User"),
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
  'surname' => 'required|string|max:255',
  'email' => 'required|string|max:255',
  'password' => 'required|string|max:255',
  'image' => 'required|mimes:',
  'group' => 'required|string|max:255',
  'role' => 'required|string|max:255',
);
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if($validator->fails){
            return response()->json([
                'message' => $validator->response,
                'code' => 400
            ]);
        }
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message' => "Event not found",
                'code' => 404
            ]);
        }
        $user->name = $request->name;
		$user->surname = $request->surname;
		$user->email = $request->email;
		$user->password = $request->password;
		if ($request->hasFile("image")) {
            $file = $request->file("image");
            $filename = time(). "_" . $file->getClientOriginalName();
            if ($user->image) {
                $oldFilePath = 'uploads/image/'.basename($user->image);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
            $file->move("uploads/image", $filename);
            $user->image = asset("uploads/image/$filename");
        }
		$user->qr_code = $request->qr_code;
		$user->group = $request->group;
		$user->role = $request->role;
        return response()->json([
            'data' => new UserResource($user),
            'code' => 200
        ]);
    }

    /**
 * @OA\Delete(
 *      path="/api/v1/user/{id}",
 *      security={{"api":{}}},
 *      operationId="user_delete",
 *      summary="Delete a User by ID",
 *      description="Remove a specific User by its ID",
 *      tags={"User API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the User to delete",
 *          @OA\Schema(type="integer")
 *      ),
 *          @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/User"),
 *      ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function destroy(string $id)
    {
        $user = User::find($id);
        if(!$user){
            return response()->json([
                'message' => "User not found",
                'code' => 404,
            ]);
        }
        $user->delete();
        return response()->json([
            'data' => new UserResource($user),
            'code' => 200
        ]);
    }
}
