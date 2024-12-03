<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'nic' => 'required|string|max:20|unique:users,nic',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'address' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8|confirmed',
            'usertype' => 'in:customer,admin', // Optional: Default is 'customer'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Create the user
        $user = User::create([
            'nic' => $request->nic,
            'name' => $request->name,
            'email' => $request->email,
            'address' => $request->address,
            'phone' => $request->phone,
            'password' => Hash::make($request->password), // Hash the password
            'usertype' => $request->usertype ?? 'customer', // Default to 'customer'
        ]);

        // Generate an API token for the user
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'status' => 'success',
            'message' => 'User registered successfully.',
            'user' => $user,
            'token' => $token, // Include the API token in the response
        ], 200);
    }

    public function login(Request $request)
    {
        // Validate email and password
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        // Attempt to find the user
        $user = User::where('email', $request->email)->first();

        // Check if user exists and password is correct
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid email or password.'
            ], 401);
        }

        // Check usertype and handle response
        if ($user->usertype === 'admin') {
            $token = $user->createToken('admin_token')->plainTextToken;
            return response()->json([
                'message' => 'Admin login successful.',
                'token' => $token,
                'user' => $user
            ], 200);
        } elseif ($user->usertype === 'customer') {
            $token = $user->createToken('customer_token')->plainTextToken;
            return response()->json([
                'message' => 'Customer login successful.',
                'token' => $token,
                'user' => $user
            ], 200);
        }

        // Default response for invalid usertype
        return response()->json([
            'message' => 'Invalid usertype.'
        ], 403);
    }

    public function logout(Request $request)
{
    // Ensure the user is authenticated
    $user = $request->user();

    if ($user) {
        // Revoke the token used for the current session
        $user->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logged out successfully.',
        ], 200);
    }

    // If no user is authenticated, return an error response
    return response()->json([
        'message' => 'No user is logged in.',
    ], 401);
}

}
