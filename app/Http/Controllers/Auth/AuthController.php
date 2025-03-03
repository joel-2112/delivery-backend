<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Exception;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Registered successfully',
                'user' => $user,
                'token' => $token
            ], Response::HTTP_CREATED);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Registration failed',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function login(LoginRequest $request)
    {
        try {
            if (!Auth::attempt($request->only('email', 'password'))) {
                return response()->json([
                    'message' => 'Invalid credentials'
                ], Response::HTTP_UNAUTHORIZED);
            }

            $user = User::where('email', $request->email)->firstOrFail();
            $token = $user->createToken('auth-token')->plainTextToken;

            return response()->json([
                'message' => 'Login successful',
                'user' => $user,
                'token' => $token
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Login failed',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function logout(Request $request)
    {
        try {
            // Step 1: Check if the user is authenticated
            if (!$request->user()) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], 401);
            }
    
            // Step 2: Revoke the token
            $request->user()->currentAccessToken()->delete();
    
            // Step 3: Return a success response
            return response()->json([
                'message' => 'Logged out successfully'
            ], 200);
    
        } catch (\Exception $e) {
            // Step 4: Handle any exceptions
            return response()->json([
                'message' => 'An error occurred during logout',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function refreshToken(Request $request)
    {
        try {
            // Step 1: Check if the user is authenticated
            if (!$request->user()) {
                return response()->json([
                    'message' => 'User not authenticated'
                ], Response::HTTP_UNAUTHORIZED);
            }

            // Step 2: Revoke the current token
            $request->user()->currentAccessToken()->delete();

            // Step 3: Create a new token
            $token = $request->user()->createToken('auth-token')->plainTextToken;

            // Step 4: Return the new token
            return response()->json([
                'message' => 'Token refreshed successfully',
                'token' => $token
            ]);
        } catch (Exception $e) {
            // Step 5: Handle any exceptions
            return response()->json([
                'message' => 'Token refresh failed',
                'error' => $e->getMessage()
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}