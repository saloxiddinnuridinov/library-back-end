<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\BookOrderResource;
use App\Http\Resources\BookResource;
use App\Models\Book;
use Illuminate\Http\Request;

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
        $bookS = Book::all();
        return response()->json([
            'data' => BookOrderResource::collection($bookS),
        ]);
    }


}
