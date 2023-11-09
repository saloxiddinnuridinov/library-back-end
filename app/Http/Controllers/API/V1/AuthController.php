<?php

namespace App\Http\Controllers\API\V1;

use App\Http\Controllers\API\v1\auth\Auth;
use App\Http\Controllers\API\v1\auth\Cache;
use App\Http\Controllers\API\v1\auth\JWTAuth;
use App\Http\Controllers\API\v1\auth\MainNotificationController;
use App\Http\Controllers\API\v1\auth\Manage;
use App\Http\Controllers\API\v1\auth\ProcessEmail;
use App\Http\Controllers\API\v1\auth\RevokePassword;
use App\Http\Controllers\Controller;
use App\Http\ValidatorResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['loginEmail']]);
    }

    /**
     * * * * * *  * * * *  * * * * * *
     * @OA\Post(
     * path="/api/v1/login",
     * summary="login User By email",
     * description="Email orqali tizimga kirish",
     * tags={"01.Auth User"},
     * @OA\RequestBody(required=true, description="Pass User credentials",
     *    @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema(type="object", required={"email","password"},
     *          @OA\Property(property="email", type="string", format="text", example="admin@gmail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="admin123"),
     *      ),
     *    ),
     * ),
     *      @OA\Response(response=200,description="Successful operation",
     *          @OA\JsonContent(ref="#/components/schemas/User"),
     *      ),
     *      @OA\Response(response=404,description="Not found",
     *          @OA\JsonContent(ref="#/components/schemas/Error"),
     *      ),
     * )
     */
    public function loginEmail(Request $request)
    {
        $rules = [
            'email' => ['required', 'string', 'max:255', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $email = trim($request->email);
        $model = User::where('email', $email)->first();
        if ($model) {
            if (Hash::check($request->password, $model->password)) {
//                    $credentials = $request->only('email', 'password');
                $credentials = [
                  'email' => $request->email,
                  'password' => $request->password,
                ];
                $token = auth('api')->attempt($credentials);
                if (!$token) {
                    return response()->json(['errors' => ['Unauthorized']], 401);
                }
                return response()->json([
                    'data' => $model,
                    'auth' => $token
                ]);
            }
            else {
                return response()->json([
                    'success' => false,
                    'message' => "Parol noto`g`ri",
                    'data' => null,
                    'code' => 400
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Foydalanuvchi topilmadi",
                'data' => null,
                'code' => 404
            ]);
        }
    }

}
