<?php

namespace App\Http\Controllers\API;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\ResponseFormatter;
use Exception;

use App\Models\User;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // Login features
    public function login(Request $request)
    {
        try {
            // validate request
            $request->validate([
                'email' => 'required|email',
                'password' => 'required'
            ]);

            // Find user by email
            $credentials = request(['email', 'password']);
            if (!Auth::attempt($credentials)) {
                return ResponseFormatter::error('Unauthorized', 401);
            }

            $user = User::where('email', $request->email)->first();
            if (!Hash::check($request->password, $user->password)) {
                throw new Exception('Invalid Password');
            }

            // Generate token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // Return response
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'Login Success!');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Something went wrong',
                'error' => $error->getMessage()
            ],  500);
        }
    }

    // Register features
    public function register(Request $request)
    {
        try {
            // validate request
            $request->validate([
                'name' => ['required', 'string', 'max:255'],
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
                'password' => ['required', Password::min(8)]
            ]);

            // Create user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password)
            ]);

            // Generate Token
            $tokenResult = $user->createToken('authToken')->plainTextToken;

            // Return response
            return ResponseFormatter::success([
                'access_token' => $tokenResult,
                'token_type' => 'Bearer',
                'user' => $user
            ], 'User Registered!');
        } catch (Exception $error) {
            return ResponseFormatter::error([
                'message' => 'Failed to register user',
                'error' => $error->getMessage()
            ],  500);
        }
    }

    // Logout features
    public function logout(Request $request)
    {
        // remove token
        $token = $request->user()->currentAccessToken()->delete();

        // return response
        return ResponseFormatter::success($token, 'Logout Success!');
    }

    // fetch user data
    public function fetch(Request $request)
    {
        // get user
        $user = $request->user();

        // return response
        return ResponseFormatter::success($user, 'User data has been fetched');
    }
}
