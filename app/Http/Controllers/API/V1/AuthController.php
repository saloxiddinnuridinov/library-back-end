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
    /**
     * * * * * *  * * * *  * * * * * *
     * @OA\Post(
     * path="/v1/api/login/phone",
     * summary="login User By Phone",
     * security={{"ApiKeyAuth":{}}},
     * description="Telefon reqam orqali tizimga kirish",
     * tags={"01.Auth User"},
     * @OA\RequestBody(required=true, description="Pass User credentials",
     *    @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema(type="object", required={"phone","password"},
     *          @OA\Property(property="phone", type="integer", format="number", example="998901234567"),
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
    public function loginPhone(Request $request)
    {
        $rules = [
            'phone' => ['required', 'integer'],
            'password' => ['required', 'string', 'min:8'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $phone = trim($request->phone);
        $model = User::where('phone', $phone)->first();
        if ($model) {
            if (Hash::check($request->password, $model->password)) {
                if (!$model->is_blocked) {
                    $credentials = $request->only('phone', 'password');
                    $token = auth('api')->attempt($credentials);
                    if (!$token) {
                        return response()->json(['errors' => ['Unauthorized']], 401);
                    }
                    $model->token = $token;
                    $model->update();
                    return response()->json([
                        'data' => $token
                    ]);
                } else {
                    return response()->json([
                        'success' => false,
                        'message' => "Bloklangan " . $model->block_reason,
                        'data' => null,
                        'code' => 403
                    ]);
                }
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

    /**
     * @OA\Post(
     * path="/v1/api/logout",
     * summary="Post a new data",
     * description="Logout User",
     * tags={"01.Auth User"},
     * security={
     *          {"api":{}},
     *          {"ApiKeyAuth":{}}
     *      },
     * @OA\Response(response=200,description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/User"),
     * ),
     * @OA\Response(response=404,description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/Error"),
     * ),
     * )
     */
    public function logout()
    {
        $user = auth('api');
        $model = User::where('id', $user->user()->id)->first();
        $model->active_token = null;
        $model->update();
        $user->logout();
        return response()->json([
            'success' => true,
            'message' => 'Hisobdan muvaffaqiyatli chiqdi',
            'data' => null,
            'code' => 200
        ]);
    }


    /**
     * @OA\Post(
     * path="/v1/api/register",
     * summary="Post a new data",
     * description="Register User ",
     * tags={"01.Auth User"},
     * @OA\RequestBody(required=true, description="User data  credentials",
     *   @OA\MediaType(mediaType="multipart/form-data",
     *       @OA\Schema(type="object", required={"name","surname","email","password"},
     *          @OA\Property(property="name", type="string", format="text", example="User"),
     *          @OA\Property(property="surname", type="string", format="text", example="Surname"),
     *          @OA\Property(property="email", type="string", format="email", example="user@gmail.com"),
     *          @OA\Property(property="password", type="string", format="password", example="admin123"),
     *      ),
     *    ),
     * ),
     *    @OA\Response(response=200,description="Successful operation",
     *        @OA\JsonContent(ref="#/components/schemas/User"),
     *      ),
     *    @OA\Response(response=404,description="Not found",
     *        @OA\JsonContent(ref="#/components/schemas/Error"),
     *      ),
     * )
     */
    public function register(Request $request)
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $User = User::where('email', $request->email)->first();
        if ($User) {
            return response()->json([
                'success' => false,
                'message' => "Avval Ro'yxatdan o'tgansiz",
                'data' => null,
                'code' => 400
            ]);
        } else {
            $data = [
                'name' => $request->name,
                'surname' => $request->surname,
                'email' => $request->email,
                'password' => $request->password,
            ];
        }
        $this->dispatch(new ProcessEmail($data));
        $text = "Yangi: User ro'yhatdan o'tdi" . "%0D%0A" . "Full name: <b>" . $data['name'] . $data['surname'] . "</b>%0D%0A" .
            "Email: <b>" . $data['email'] . "</b>";

        Manage::sendTelegram(170, $text);

        return response()->json([
            'success' => true,
            'message' => $request->email . " ga faollashtirish kodi yuborildi",
            'data' => null,
            'code' => 200
        ]);
    }

    /**
     * @OA\Post(
     * path="/api/v1/refresh",
     * summary="Post a new data",
     * description="Logout User ",
     * tags={"01.Auth User"},
     * security={{ "User": {} }},
     * @OA\Response(response=200,description="Successful operation",
     *     @OA\JsonContent(ref="#/components/schemas/User"),
     * ),
     * @OA\Response(response=404,description="Not found",
     *     @OA\JsonContent(ref="#/components/schemas/Error"),
     * ),
     * )
     */
    public function refresh()
    {
        return response()->json([
            'success' => true,
            'message' => auth()->guard('User')->user(),
            'data' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ],
            'code' => 200
        ]);
    }


    /**
     * @OA\Post (
     *      path="/api/v1/resend-code",
     *      tags={"01.Auth User"},
     *      operationId="resend-activate-code",
     *      summary="Verification",
     *      description="Resend activation code",
     *       @OA\RequestBody(
     *          required=true,
     *          description="Resend",
     *          @OA\MediaType(
     *              mediaType="multipart/form-data",
     *              @OA\Schema(
     *                  type="object",
     *                  required={"email"},
     *                  @OA\Property(property="email", type="string", format="email", example="azimxalilov5443@gmail.com"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="User created successfully"),
     *              @OA\Property(property="user", type="string", example="{name,surname . . .}"),
     *          ),
     *      )
     * )
     */
    public function resend(Request $request)
    {
        $rules = [
            'email' => ['required', 'string', 'max:255', 'email'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $email = trim($request->email);

        $getCache = Cache::store('database')->get($email);

        if ($getCache) {
            $User = User::where('email', $email)->first();
            if (!$User) {
                $sms = new MainNotificationController();
                $message = [
                    'title' => 'Programmer UZ',
                    'verify_code' => $getCache['verify_code'],
                    'name' => $getCache['name'],
                ];
                return $sms->mail($email, $message, 'revoke_password');
            } else {
                $sms = new MainNotificationController();
                $message = [
                    'title' => 'Programmer UZ',
                    'verify_code' => $getCache['verify_code'],
                    'name' => $User->name,
                ];
                return $sms->mail($email, $message, 'revoke_password');
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => "Vaqt tugadi",
                'data' => null,
                'code' => 200
            ]);
        }

    }

    /**
     * @OA\Post (
     *      path="/api/v1/activate-user",
     *      tags={"01.Auth User"},
     *      operationId="active_user",
     *      summary="Verification",
     *      description="Ro'yxatdan o'tgandan so'ng userni aktiv qilish",
     *       @OA\RequestBody(required=true,description="Verify",
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(type="object",
     *                  required={"email","verify_code"},
     *                  @OA\Property(property="email", type="string", format="email", example="azimxalilov5443@gmail.com"),
     *                  @OA\Property(property="verify_code", type="integer", format="number", example=123456),
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200,description="Success",
     *          @OA\JsonContent(@OA\Property(property="message", type="string", example="SUCCESS")),
     *      )
     * )
     */

    public function activateUser(Request $request)
    {
        $rules = [
            'email' => 'required|email|unique:Users,email',
            'verify_code' => 'required|numeric',
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }

        $email = trim($request->email);

        $getCache = Cache::store('database')->get($email);

        if (Cache::store('database')->has($email)) {

            if ($getCache['verify_code'] == $request->verify_code) {
                try {
                    $User = User::where('email', $email)->first();
                    if (!$User) {
                        $User = new User();
                        $User->name = $getCache['name'];
                        $User->surname = $getCache['surname'];
                        $User->email = $email;
                        $User->password = Hash::make($getCache['password']);
                        $User->save();
                    } else {
                        $User->name = $getCache['name'];
                        $User->surname = $getCache['surname'];
                        $User->email = $email;
                        $User->password = Hash::make($getCache['password']);
                    }
                    $token = JWTAuth::fromUser($User);
                    $User->active_token = $token;
                    $User->update();
                    $authorization = [
                        'user' => $User,
                        'token' => $token,
                        'type' => 'bearer',
                    ];

                    Cache::store('database')->forget($email);

                    $text = "Yangi: User Aktiv bo`ldi" . "%0D%0A" . "Full name: <b>" . $User->name . " " . $User->surname . "</b>%0D%0A" .
                        "Email: <b>" . $User->email . "</b>";
                    Manage::sendTelegram(170, $text);

                    return response()->json([
                        'success' => true,
                        'message' => "Akkount faollashdi",
                        'data' => $authorization,
                        'code' => 200
                    ]);
                } catch (\Exception $exception) {
                    $text = json_encode($exception->getMessage(), JSON_PRETTY_PRINT);
                    Manage::sendTelegram(858, $text);
                    return response()->json([
                        'success' => false,
                        'message' => "Xatolik yuz berdi keyinroq urinib ko'ring",
                        'data' => null,
                        'code' => 400
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'kod xato',
                    'data' => $getCache['verify_code'],
                    'code' => 400
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'foydalanuvchi topilmadi',
                'data' => null,
                'code' => 404
            ]);
        }
    }

    /**
     * @OA\Post (
     *      path="/api/v1/revoke-password",
     *      tags={"01.Auth User"},
     *      operationId="revoke_password",
     *      summary="revoke Password",
     *      description="Parolni esdan chiqardi tizimga kirish uchun yangi parol so'rash",
     *       @OA\RequestBody(required=true,description="Reset Password",
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(type="object",required={"email"},
     *                  @OA\Property(property="email", type="string", format="email", example="azimxalilov5443@gmail.com"),
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200,description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="User created successfully"),
     *              @OA\Property(property="user", type="string", example="{name,surname . . .}"),
     *              @OA\Property(property="authorization", type="string", example="{token,type}"),
     *          ),
     *      )
     * )
     */

    public function revokePassword(Request $request)
    {
        $rules = [
            'email' => ['required', 'exists:Users,email'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $login = $request->email;
        $User = User::where('email', $login)->first();

        if (!$User) {
            return response()->json([
                'success' => false,
                'message' => "Foydalanuvchi topilmadi!",
                'data' => null,
                'code' => 404
            ]);
        }

        $data = [
            'name' => $User->name,
            'surname' => $User->surname,
            'email' => $User->email,
        ];

        $this->dispatch(new RevokePassword($data));

        return response()->json([
            'success' => true,
            'message' => $request->email . " ga tasdiqlash kodi yuborildi",
            'data' => null,
            'code' => 200
        ]);
    }

    /**
     * @OA\Post (
     *      path="/api/v1/check-verify-code",
     *      tags={"01.Auth User"},
     *      operationId="check_verify_code",
     *      summary="revoke Password",
     *      description="Activatsiya kodi to'g'riligini tekshirish",
     *       @OA\RequestBody(required=true,description="Check code",
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(type="object",required={"email","verify_code"},
     *                  @OA\Property(property="email", type="string", format="email", example="azimxalilov5443@gmail.com"),
     *                  @OA\Property(property="verify_code", type="integer", format="number", example=123456),
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200,description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="User created successfully"),
     *              @OA\Property(property="user", type="string", example="{name,surname . . .}"),
     *              @OA\Property(property="authorization", type="string", example="{token,type}"),
     *          ),
     *      )
     * )
     */

    public function checkVerifyCode(Request $request): \Illuminate\Http\JsonResponse
    {
        $rules = [
            'email' => ['required', 'exists:Users,email'],
            'verify_code' => ['required', 'numeric'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }

        $getCache = Cache::store('database')->get(trim($request->email));
        if (Cache::store('database')->has(trim($request->email))) {
            if ($getCache['verify_code'] == $request->verify_code) {
                try {
                    // Cache::store('database')->forget($request->email);
                    return response()->json([
                        'success' => true,
                        'message' => 'Kod To`g`ri kiritildi',
                        'data' => $getCache['verify_code'],
                        'code' => 200
                    ]);
                } catch (\Exception $exception) {
                    $text = json_encode($exception->getMessage(), JSON_PRETTY_PRINT);
                    Manage::sendTelegram(858, $text);
                    return response()->json([
                        'success' => false,
                        'message' => 'Xatolik yuz berdi',
                        'data' => null,
                        'code' => 400
                    ]);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'kod xato',
                    'data' => $request->verify_code,
                    'code' => 400
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Foydalanuvchi topilmadi',
                'data' => null,
                'code' => 404
            ]);
        }
    }

    /**
     * @OA\Post (
     *      path="/api/v1/reset-password-verify",
     *      operationId="reset_password_verify",
     *      tags={"01.Auth User"},
     *      summary="reset Password Verify",
     *      description="Tizimga kirish uchun tasdiqlash parolini to'g'ri yozgan bulsa endi yangi parol kiritish",
     *       @OA\RequestBody(required=true,description="Reset Password",
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(type="object",
     *                  required={"email","password","password_confirmation","verify_code"},
     *                  @OA\Property(property="email", type="string", format="email", example="azimxalilov5443@gmail.com"),
     *                  @OA\Property(property="verify_code", type="integer", format="number", example=123456),
     *                  @OA\Property(property="password", type="string", format="text", example="admin123"),
     *                  @OA\Property(property="password_confirmation", type="string", format="text", example="admin123"),
     *              )
     *          )
     *      ),
     *      @OA\Response(
     *          response=200,
     *          description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="status", type="string", example="success"),
     *              @OA\Property(property="message", type="string", example="User created successfully"),
     *              @OA\Property(property="user", type="string", example="{name,surname . . .}"),
     *              @OA\Property(property="authorization", type="string", example="{token,type}"),
     *          ),
     *      )
     * )
     */
    public function resetPasswordVerify(Request $request): \Illuminate\Http\JsonResponse
    {
        $rules = [
            'email' => ['required', 'exists:Users,email'],
            'verify_code' => ['required', 'numeric'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        }
        $email = trim($request->email);

        $getCache = Cache::store('database')->get($email);

        if (Cache::store('database')->has($email)) {
            if ($getCache['verify_code'] == $request->verify_code) {
                $User = User::where('email', $email)->first();
                $User->password = Hash::make($request->password);
                $token = JWTAuth::fromUser($User);
                $User->active_token = $token;
                $User->update();

                return response()->json([
                    'success' => true,
                    'message' => 'Parol o`zgartirildi',
                    'data' => [
                        'user' => $User,
                        'authorization' => [
                            'token' => $token,
                            'type' => 'bearer',
                        ]
                    ],
                    'code' => 200
                ]);

            } else {
                return response()->json([
                    'success' => false,
                    'message' => "Tasdiqlash kodi xato",
                    'data' => null,
                    'code' => 400
                ]);
            }
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Foydalanuvchi topilmadi',
                'data' => null,
                'code' => 404
            ]);
        }
    }

    /**
     * @OA\Post(
     *      path="/v1/change-password",
     *      tags={"01.Auth User"},
     *      security={{ "User": {} }},
     *      summary="Change Password",
     *      description="Change Password",
     *      @OA\RequestBody(required=true, description="Login page",
     *          @OA\MediaType(mediaType="multipart/form-data",
     *              @OA\Schema(type="object", required={"password", "password_confirmation"},
     *                  @OA\Property(property="password", type="string", format="password", example="admin123"),
     *                  @OA\Property(property="password_confirmation", type="string", format="password", example="admin123"),
     *              )
     *          )
     *      ),
     *      @OA\Response(response=200, description="Success",
     *          @OA\JsonContent(
     *              @OA\Property(property="email", type="string", example="admin@gmail.com"),
     *              @OA\Property(property="password", type="string", example="admin123"),
     *          ),
     *      ),
     *      @OA\Response(response=422,description="invalid",
     *          @OA\JsonContent(
     *              @OA\Property(property="msg", type="string", example="fail"),
     *          )
     *      )
     * )
     */
    public function changePassword(Request $request)
    {
        $rules = [
            'password' => 'required|min:6|confirmed',
        ];
        $validator = new ValidatorResponse();
        $validator->check($request, $rules);
        if ($validator->fails) {
            return response()->json($validator->response, 400);
        } else {
            $user = auth('User')->user();
            $model = User::where('id', $user->id)->first();
            if ($model) {
                $model->password = Hash::make($request->password);
                $model->update();
                return response()->json([
                    'success' => true,
                    'message' => "Parol o'zgartirildi",
                    'data' => [],
                    'code' => 200
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => "User topilmadi",
                    'data' => [],
                    'code' => 404
                ]);
            }
        }
    }
}
