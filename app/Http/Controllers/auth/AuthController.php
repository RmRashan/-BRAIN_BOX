<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function login(Request $request)
    {


        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
        ];


        $rules = [
            'username' => 'required',
            'password' => 'required',
        ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }


        if(Auth::attempt($credentials)) {


            $user = User::where('username', $request->username)->first();


       

            return response()->json([
                'authentication' => 'success'
            ], 200);

        } else {
            return response()->json([
                'error' => 'Invalid username or Password'
            ], 400);
        }
    }

    public function  register(Request $request)
    {


        $request->username ;
        $rules = [
            'email' => 'required|email|unique:users',
                'password' => 'required',
                'user_type' => 'required',
            ];


        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        } else {



            $user = User::create([
                'username' => $request->email,
                'email' => $request->email,
                'user_type' => $request->user_type,
                'password' => Hash::make($request->password),
            ]);
            $token = $user->createToken('authToken');


            return response()->json([
                'access_token' => $token,
                'token_type' => 'Bearer',
                'user' => $user,
                'status' => 'success'
            ]);
        }
    
    }
    public function  logout() {}
}
