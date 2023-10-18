<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Http\Resources\BookResource;
use App\Http\ValidatorResponse;
use Illuminate\Http\Request;

  /**
 * @OA\Schema(
 *     schema="Book",
 *     title="Book title",
 *     @OA\Property(property="success", type="boolean", example=true),
 *     @OA\Property(property="message", type="string", example="Successfully"),
 *     @OA\Property(
 *         property="data",
 *         type="array",
 *         @OA\Items(
 *              @OA\Property(property="id", type="integer"),
 *				@OA\Property(property="name", type="string"),
 *				@OA\Property(property="status", type="string"),
 *				@OA\Property(property="description", type="string"),
 *				@OA\Property(property="created_at", type="string", format="date-time"),
 *				@OA\Property(property="updated_at", type="string", format="date-time")
 *         )
 *     ),
 *     @OA\Property(property="code", type="integer", example=200),
 * )
 */

class BookController extends Controller
{
    /**
 *@OA\Get(
 *      path="/api/v1/book",
 *      security={{"api":{}}},
 *      operationId="book_index",
 *      summary="Get all Books",
 *      description="Retrieve all Books",
 *      tags={"Book API CRUD"},
 *      @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Book"),
 *      ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function index()
    {
        $bookS = Book::all();
        return response()->json([
            'data' => BookResource::collection($bookS),
        ]);
    }

    /**
 * @OA\Get(
 *      path="/api/v1/book/{id}",
 *      security={{"api":{}}},
 *      operationId="book_show",
 *      summary="Get a single Book by ID",
 *      description="Retrieve a single Book by its ID",
 *      tags={"Book API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the Book to retrieve",
 *          @OA\Schema(type="integer")
 *      ),
 *      @OA\Response(response=200,description="Successful operation",
 *          @OA\JsonContent(ref="#/components/schemas/Book"),
 *     ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function show(string $id)
    {
        $book = Book::find($id);
        if(!$book){
            return response()->json([
                'message' => "Book not found",
                'code' => 403,
            ]);
        }

        return response()->json([
            'data' => new BookResource($book),
            'code' => 200
        ]);
    }

    /**
 * @OA\Post(
 *      path="/api/v1/book",
 *      security={{"api":{}}},
 *      operationId="book_store",
 *      summary="Create a new Book",
 *      description="Add a new Book",
 *      tags={"Book API CRUD"},
 *      @OA\RequestBody(required=true, description="Book save",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required={"name", "status"},
 *                  @OA\Property(property="name", type="string", example=""),
 *					@OA\Property(property="status", type="string", example=""),
 *					@OA\Property(property="description", type="string", example="")
 *              )
 *          )
 *      ),
 *       @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Book"),
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
  'status' => 'required|string|max:255',
);
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if($validator->fails){
            return response()->json([
                'message' => $validator->response,
                'code' => 400
            ]);
        }
        $book = new Book();
        $book->name = $request->name;
		$book->status = $request->status;
		$book->description = $request->description;
        $book->save();
        return response()->json([
            'data' => new BookResource($book),
            'code' => 200
        ]);
    }

    /**
 * @OA\Post(
 *      path="/api/v1/book/{id}",
 *      security={{"api":{}}},
 *      operationId="book_update",
 *      summary="Update a Book by ID",
 *      description="Update a specific Book by its ID",
 *      tags={"Book API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the Book to update",
 *          @OA\Schema(type="integer")
 *      ),
 *           @OA\RequestBody(required=true, description="Book save",
 *           @OA\MediaType(mediaType="multipart/form-data",
 *              @OA\Schema(type="object", required={"name", "status"},
 *                  @OA\Property(property="name", type="string", example=""),
 *					@OA\Property(property="status", type="string", example=""),
 *					@OA\Property(property="description", type="string", example=""),
 *				    @OA\Property(property="_method", type="string", example="PUT", description="Read-only: This property cannot be modified."),
 *              )
 *          )
 *      ),
 *
 *     @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Book"),
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
  'status' => 'required|string|max:255',
);
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if($validator->fails){
            return response()->json([
                'message' => $validator->response,
                'code' => 400
            ]);
        }
        $book = Book::find($id);
        if(!$book){
            return response()->json([
                'message' => "Event not found",
                'code' => 404
            ]);
        }
        $book->name = $request->name;
		$book->status = $request->status;
		$book->description = $request->description;
        return response()->json([
            'data' => new BookResource($book),
            'code' => 200
        ]);
    }

    /**
 * @OA\Delete(
 *      path="/api/v1/book/{id}",
 *      security={{"api":{}}},
 *      operationId="book_delete",
 *      summary="Delete a Book by ID",
 *      description="Remove a specific Book by its ID",
 *      tags={"Book API CRUD"},
 *      @OA\Parameter(
 *          name="id",
 *          in="path",
 *          required=true,
 *          description="ID of the Book to delete",
 *          @OA\Schema(type="integer")
 *      ),
 *          @OA\Response(response=200,description="Successful operation",
 *           @OA\JsonContent(ref="#/components/schemas/Book"),
 *      ),
 *      @OA\Response(response=404,description="Not found",
 *          @OA\JsonContent(ref="#/components/schemas/Error"),
 *      ),
 * )
 */
    public function destroy(string $id)
    {
        $book = Book::find($id);
        if(!$book){
            return response()->json([
                'message' => "Book not found",
                'code' => 404,
            ]);
        }
        $book->delete();
        return response()->json([
            'data' => new BookResource($book),
            'code' => 200
        ]);
    }
}
