<?php

namespace App\Http\Controllers\auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
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

            // Generate token
            $token = $user->createToken('authToken')->accessToken; // Get the access token

            return response()->json([
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'user' => $user,
                    'status' => 'success'
                ]);


          

        } else {
            return response()->json([
                'error' => 'Invalid username or Password'
            ], 400);
        }
    }

    public function register(Request $request)
    {
        // Define validation rules
        $rules = [
            'firstName' => 'required|string|max:255',
            'lastName' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string',
            'userType' => 'required|in:student,he_student,instructor,agent',
            'paymentReceipt' => 'nullable|file|mimes:jpeg,png,jpg,gif,svg|max:2048', // Validation for file upload
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'error' => $validator->errors()
            ], 400);
        }

        // Handle file upload
        $imageUrl = null; // Initialize image URL
        if ($request->hasFile('paymentReceipt')) {

            $imageName = time(). "_".$request->userType. "_". $request->email . ".". $request->paymentReceipt->guessExtension();// usetTyep+username+imagextention
            
             Storage::disk('publicuploads')->put($imageName, file_get_contents($request->paymentReceipt));  // Store file in 'storage/app/public/'

            // $path = Storage::put('images/'. $imageName, $request->file('paymentReceipt'));
            // $imagePath = $request->file('paymentReceipt')->store('uploads', 'public'); // Store file in 'storage/app/public/uploads'
            //  $imageUrl = asset('storage/' . $imageName); // Generate the public URL for the image
        }

        // Create user
        $user = User::create([
            'first_name' => $request->firstName,
            'last_name' => $request->lastName,
            'username' => $request->email,  // Optional field
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'user_type' => $request->userType,
            'image_url' =>$imageName, // Store the image URL if uploaded
        ]);

        // Generate token
        $token = $user->createToken('authToken')->accessToken; // Get the access token

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => $user,
            'status' => 'success'
        ]);
    }


    public function  users() {

        $user = Auth::user();

        return response()->json([
            'user' => $user,
            'status' => 'success'
        ]);

    }
}
