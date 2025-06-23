<?php

namespace App\Http\Controllers;

use Exception;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    
    public function login(Request $request)
    {
        try {
            // Validate input
            $request->validate([
                'email' => 'required|email',
                'password' => 'required',
            ]);

            // Find user
            $user = User::where('email', $request->email)->first();

            // Check credentials
            if (! $user || ! Hash::check($request->password, $user->password)) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid credentials.',
                    'data' => null,
                ], 401);
            }

            // Generate token
            $token = $user->createToken('api-token')->plainTextToken;

            return response()->json([
                'status' => true,
                'message' => 'Login successful.',
                'data' => [
                    'user' => $user,
                    'token' => $token,
                ],
            ], 200);
        } catch (ValidationException $e) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error.',
                'data' => $e->errors(),
            ], 422);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred during login.',
                'data' => ['error' => $e->getMessage()],
            ], 500);
        }
    }

      public function getUserDetails(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated.',
                    'data' => null,
                ], 401);
            }

            return response()->json([
                'status' => true,
                'message' => 'User details retrieved successfully.',
                'data' => $user,
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'An error occurred.',
                'data' => ['error' => $e->getMessage()],
            ], 500);
        }
    }
}
