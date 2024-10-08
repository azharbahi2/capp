<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use JWTAuth;

use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // public function register(Request $request)
    // {
    //     $data = $request->only(['name', 'email', 'password']);
    //     $validator = Validator::make($data, [
    //         'name' => [
    //             'required',
    //             'string'
    //         ],
    //         'email' => [
    //             'required',
    //             'email',
    //             'unique:users'
    //         ],
    //         'password' => [
    //             'required',
    //             'string',
    //             'min:6',
    //             'max:50'
    //         ]
    //     ]);
    //     if ($validator->fails()) {
    //         return response()->json([
    //             'errors' => $validator->getMessageBag()
    //                 ->toArray()
    //         ], Response::HTTP_BAD_REQUEST);
    //     }
    //     User::create([
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'password' => bcrypt($data['password'])
    //     ]);
    //     $credentials = $request->only(['email', 'password']);
    //     if (!$token = auth('api')->attempt($credentials)) {
    //         return response()->json([
    //             'error' => 'Unauthorized'
    //         ], Response::HTTP_UNAUTHORIZED);
    //     }
    //     return $this->respondWithToken($token);
    // }

    public function login(Request $request)
    {
        $credentials = $request->only(['email', 'password']);
        if (!$token = JWTAuth::attempt($credentials)) {


            return response()->json([
                'error' => 'Unauthorized'
            ], Response::HTTP_UNAUTHORIZED);
        }
        return $this->respondWithToken($token);
    }

    public function user()
    {
        return response()->json(auth('api')->user());
    }

    public function logout()
    {
        auth('api')->logout();
        return response()->json([
            'message' => 'Successfully logged out'
        ]);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }

    protected function respondWithToken($token)
    {
        $user = JWTAuth::user();
        return response()->json([
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => config('jwt.ttl') * 60
        ]);
    }

}
