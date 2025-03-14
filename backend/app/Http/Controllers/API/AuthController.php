<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Support\Str;
use App\Jobs\VerifyUserJobs;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'register', 'CheckEmail']]);
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string|min:6'
        ]);

        if($validator->fails()){
            return response()->json($validator->errors(), 422);
        }

        if(!$token = JWTAuth::attempt($validator->validated())){
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        return $this->createNewToken($token);
    }

    public function register(Request $request){
        $validator = Validator::make($request->all(),[
                'name' => 'required|string|between:2,100',
                'email' => 'required|string|email|max:100|unique:users',
                'password' => 'required|string|confirmed|min:6'
            ]);

        
        if($validator->fails()){
            return response()->json($validator->errors()->toJson(), 400);
        }

        $user = User::create(array_merge(
            $validator->validated(),
            [
                'password' => bcrypt($request->password),
                'slug'     => Str::random(15),
                'token'   => Str::random(20),
                'status'   => 'active'
            ]
        ));

        if($user){
            $details = [
                'name' => $user->name,
                'email' => $user->email,
                'hasEmail' => Crypt::encrypt($user->email),
                'token' => $user->token
            ];

            dispatch(new VerifyUserJobs($details));
        }

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user
        ], 201);
    }

    public function logout(){
        auth()->logout();
        return response()->json([
            'message' => 'User successfully signed out'
        ]);
    }

    public function refresh(){
        return $this->createNewToke(auth()->refresh());
    }

    public function userProfile(){
        return response()->json(auth()->user());
    }

    protected function createNewToken($token){
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            // 'expires_in' => JWTAuth::factory()->getTTL() * 60,
            'user' => auth()->user()
        ]);
    }

    public function CheckEmail($token, $hasEmail){
        $user = User::where([
            ['email', Crypt::decrypt($hasEmail)],
            ['token', $token]
        ])->first();

        if($token == $user->token){
            $user->update([
                'verify' => true,
                'token'  => null,
            ]);

            return redirect()->to('http://192.168.1.140:8000/verify/success');
        }
        return redirect()->to('http://192.168.1.140:8000/verify/invalid_token');
    }
}
