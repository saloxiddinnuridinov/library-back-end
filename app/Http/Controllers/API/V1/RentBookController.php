<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\RentBook;
use App\Http\Resources\RentBookResource;
use App\Http\ValidatorResponse;
use Illuminate\Http\Request;

  /**
 * @OA\Schema(
 *     schema="RentBook",
 *     title="RentBook title",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Successfully"),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *              @OA\Property(property="id", type="integer"),
 *				@OA\Property(property="user_id", type="integer"),
 *				@OA\Property(property="book_id", type="integer"),
 *				@OA\Property(property="star_time", type="string", format="date-time"),
 *				@OA\Property(property="end_time", type="string", format="date-time"),
 *				@OA\Property(property="is_taken", type="boolean"),
 *				@OA\Property(property="created_at", type="string", format="date-time"),
 *				@OA\Property(property="updated_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Property(property="code", type="integer", example=200),
 * )
 */

class RentBookController extends Controller
{
    /**
 *@OA\Get(
 *      path="/api/v1/rent_book",
 *      security={{"api":{}}},
 *      operationId="rent_book_index",
 *      summary="Get all RentBooks",
 *      description="Retrieve all RentBooks",
 *      tags={"RentBook API CRUD"},
 *      @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/RentBook"),
 *      ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function index()
    {
        $rent_bookS = RentBook::all();
        return response()->json([
            'data' => RentBookResource::collection($rent_bookS),
        ]);
    }

    /**
 * @OA\Get(
 *      path="/api/v1/rent_book/{id}",
 *      security={{"api":{}}},
 *      operationId="rent_book_show",
 *      summary="Get a single RentBook by ID",
 *      description="Retrieve a single RentBook by its ID",
 *      tags={"RentBook API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the RentBook to retrieve",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(response=200,description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/RentBook"),
 *     ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function show(string $id)
    {
        $rent_book = RentBook::find($id);
        if(!$rent_book){
            return response()->json([
                'message' => "RentBook not found",
                'code' => 403,
            ]);
        }

        return response()->json([
            'data' => new RentBookResource($rent_book),
            'code' => 200
        ]);
    }

    /**
 * @OA\Post(
 *      path="/api/v1/rent_book",
 *      security={{"api":{}}},
 *      operationId="rent_book_store",
 *      summary="Create a new RentBook",
 *      description="Add a new RentBook",
 *      tags={"RentBook API CRUD"},
 *      @OA\RequestBody(required=true, description="RentBook save",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required={"user_id", "book_id", "star_time", "end_time", "is_taken"},
 *                  @OA\Property(property="user_id", type="integer", example=""),
 *					@OA\Property(property="book_id", type="integer", example=""),
 *					@OA\Property(property="star_time", type="string", format="date-time", example=""),
 *					@OA\Property(property="end_time", type="string", format="date-time", example=""),
 *					@OA\Property(property="is_taken", type="boolean", example="")
 *              )
 *          )
 *      ),
 *       @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/RentBook"),
 *      ),
 *       @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function store(Request $request)
    {
        $rules = array (
  'user_id' => 'required|integer',
  'book_id' => 'required|integer',
  'star_time' => 'required|date',
  'end_time' => 'required|date',
  'is_taken' => 'required|boolean',
);
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if($validator->fails){
            return response()->json([
                'message' => $validator->response,
                'code' => 400
            ]);
        }
        $rent_book = new RentBook();
        $rent_book->user_id = $request->user_id;
		$rent_book->book_id = $request->book_id;
		$rent_book->star_time = $request->star_time;
		$rent_book->end_time = $request->end_time;
		$rent_book->is_taken = $request->is_taken;
        $rent_book->save();
        return response()->json([
            'data' => new RentBookResource($rent_book),
            'code' => 200
        ]);
    }

    /**
 * @OA\Post(
 *      path="/api/v1/rent_book/{id}",
 *      security={{"api":{}}},
 *      operationId="rent_book_update",
 *      summary="Update a RentBook by ID",
 *      description="Update a specific RentBook by its ID",
 *      tags={"RentBook API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the RentBook to update",
 *          @OA\Schema(type="integer")
 *      ),
 *           @OA\RequestBody(required=true, description="RentBook save",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required={"user_id", "book_id", "star_time", "end_time", "is_taken"},
 *                  @OA\Property(property="user_id", type="integer", example=""),
 *					@OA\Property(property="book_id", type="integer", example=""),
 *					@OA\Property(property="star_time", type="string", format="date-time", example=""),
 *					@OA\Property(property="end_time", type="string", format="date-time", example=""),
 *					@OA\Property(property="is_taken", type="boolean", example=""),
 *				    @OA\Property(property="_method", type="string", example="PUT", description="Read-only: This property cannot be modified."),
 *              )
 *          )
 *      ),
 *
 *     @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/RentBook"),
 *      ),
 *     @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function update(Request $request, string $id)
    {
        $rules = array (
  'user_id' => 'required|integer',
  'book_id' => 'required|integer',
  'star_time' => 'required|date',
  'end_time' => 'required|date',
  'is_taken' => 'required|boolean',
);
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if($validator->fails){
            return response()->json([
                'message' => $validator->response,
                'code' => 400
            ]);
        }
        $rent_book = RentBook::find($id);
        if(!$rent_book){
            return response()->json([
                'message' => "Event not found",
                'code' => 404
            ]);
        }
        $rent_book->user_id = $request->user_id;
		$rent_book->book_id = $request->book_id;
		$rent_book->star_time = $request->star_time;
		$rent_book->end_time = $request->end_time;
		$rent_book->is_taken = $request->is_taken;
        return response()->json([
            'data' => new RentBookResource($rent_book),
            'code' => 200
        ]);
    }

    /**
 * @OA\Delete(
 *      path="/api/v1/rent_book/{id}",
 *      security={{"api":{}}},
 *      operationId="rent_book_delete",
 *      summary="Delete a RentBook by ID",
 *      description="Remove a specific RentBook by its ID",
 *      tags={"RentBook API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the RentBook to delete",
 *          @OA\Schema(type="integer")
 *      ),
 *          @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/RentBook"),
 *      ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function destroy(string $id)
    {
        $rent_book = RentBook::find($id);
        if(!$rent_book){
            return response()->json([
                'message' => "RentBook not found",
                'code' => 404,
            ]);
        }
        $rent_book->delete();
        return response()->json([
            'data' => new RentBookResource($rent_book),
            'code' => 200
        ]);
    }
}
