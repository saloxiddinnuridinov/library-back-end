<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\StudentResource;
use App\Models\User;
use Illuminate\Http\Request;

class StudentController extends Controller
{
    /**
     *@OA\Get(
     *      path="/api/v1/get-student",
     *      security={{"api":{}}},
     *      description="Retrieve all Student",
     *      tags={"Admin API"},
     *      @OA\Response(response=200,description="Successful operation",
     *           @OA\JsonContent(ref="#/components/schemas/Order"),
     *      ),
     *      @OA\Response(response=404,description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/Error"),
     *      ),
     * )
     */
    public function index(){
        $model = User::orderByDesc('created_at')->where('role', 'student')->get();
        return response()->json([
            'data' => StudentResource::collection($model),
        ]);
    }
}
