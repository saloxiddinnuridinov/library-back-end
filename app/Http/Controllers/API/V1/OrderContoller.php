<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\BookOrderResource;
use App\Http\Resources\API\V1\BookReadResource;
use App\Http\Resources\API\V1\BookShowOrderResource;
use App\Http\Resources\BookResource;
use App\Models\Book;
use App\Models\RentBook;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

/**
 * @OA\Schema(
 *     schema="Order",
 *     title="Order title",
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
class OrderContoller extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => []]);
    }

    /**
     *@OA\Get(
     *      path="/api/v1/get-book",
     *      security={{"api":{}}},
     *      description="Retrieve all Student",
     *      tags={"Student API"},
     *      @OA\Response(response=200,description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/Order"),
     *      ),
     *      @OA\Response(response=404,description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/Error"),
     *      ),
     * )
     */
    public function getBook()
    {
        $bookS = Book::orderByDesc('id')->get();
        return response()->json([
            'data' => BookOrderResource::collection($bookS),
        ]);
    }


    /**
     * @OA\Get(
     *      path="/api/v1/get-book/{id}",
     *      security={{"api":{}}},
     *      summary="Get a single Book by ID",
     *      description="Retrieve a single Book by its ID",
     *      tags={"Student API"},
     *      @OA\Parameter(
     *          name="id",
     *          in="path",
     *          required=true,
     *          description="ID of the Book to retrieve",
     *          @OA\Schema(type="integer")
     *      ),
     *      @OA\Response(response=200,description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/Order"),
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
            'data' => new BookShowOrderResource($book),
            'code' => 200
        ]);
    }

    /**
     *@OA\Get(
     *      path="/api/v1/read-book",
     *      security={{"api":{}}},
     *      description="Retrieve all Student",
     *      tags={"Student API"},
     *      @OA\Response(response=200,description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/Order"),
     *      ),
     *      @OA\Response(response=404,description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/Error"),
     *      ),
     * )
     */
    public function readBook()
    {
        $id = auth('api')->user()->id;
        $book = RentBook::select('id', 'book_id', 'is_taken', 'created_at')->where('user_id',$id)->where('is_taken', 0)->get();
        if(!$book){
            return response()->json([
                'message' => "Book not found",
                'code' => 403,
            ]);
        }
        return response()->json([
            'data' => BookReadResource::collection($book),
            'code' => 200
        ]);
    }

    /**
     *@OA\Get(
     *      path="/api/v1/existing-book",
     *      security={{"api":{}}},
     *      description="Retrieve all Student",
     *      tags={"Student API"},
     *      @OA\Response(response=200,description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/Order"),
     *      ),
     *      @OA\Response(response=404,description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/Error"),
     *      ),
     * )
     */
    public function existingBook(){
        $id = auth('api')->user()->id;
        $book = RentBook::select('id', 'book_id', 'is_taken', 'created_at')->where('user_id',$id)->where('is_taken', 1)->get();
        if(!$book){
            return response()->json([
                'message' => "Book not found",
                'code' => 403,
            ]);
        }
        return response()->json([
            'data' => BookReadResource::collection($book),
            'code' => 200
        ]);
    }

    /**
     * @OA\Post(
     *      path="/api/v1/qr-code",
     *      security={{"api":{}}},
     *      description="Add a new Subject",
     *      tags={"Student API"},
     *      @OA\RequestBody(required=true, description="Subject save",
     *           @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(type="object", required={},
     *
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
    public function qrCode()
    {
        $ID = auth('api')->user()->id;
        $user = User::where('id', $ID)->first();
        if ($user) {

        }
        return $user;
    }




}
