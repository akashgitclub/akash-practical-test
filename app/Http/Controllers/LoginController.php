<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'  => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success'  => false,
                'message' => 'Account does not exist. Please register.'
            ], 400);
        }

        if ($user->status == '0') {
            return response()->json([
                'success'  => false,
                'message' => 'Your account is not active.'
            ], 403);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success'  => false,
                'message' => 'Invalid password.'
            ], 401);
        }

        if ($user->role !== 'admin') {
            return response()->json([
                'success'  => false,
                'message' => 'You are not allowed to login from here'
            ], 403);
        }

        Auth::login($user);

        $userId = Crypt::encrypt($user->id);

        return response()->json([
            'success'  => true,
            'message' => 'Login successful!',
            'data'    => [
                'user_id' => $userId
            ],
        ], 200);
    }


    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
