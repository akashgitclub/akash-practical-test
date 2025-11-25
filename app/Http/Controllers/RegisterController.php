<?php

namespace App\Http\Controllers;

use App\Mail\OtpMail;
use App\Models\MailLog;
use App\Models\OtpLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:50',
            'last_name'  => 'required|string|max:50',
            'email'      => 'required|email|max:100',
            'password'   => 'required|min:6',
            'role'       => 'required|in:admin,customer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        DB::beginTransaction();
        try {

            $email = $request->email;

            if (User::where('email', $email)->where('status', 1)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Email already registered.'
                ], 409);
            }

            $user = User::create([
                'first_name' => $request->first_name,
                'last_name'  => $request->last_name,
                'email'      => $email,
                'role'       => $request->role,
                'password'   => Hash::make($request->password),
                'status'     => 0,
            ]);

            $otp = rand(100000, 999999);
            $expiresAt = now()->addMinutes(5);

            OtpLog::where('user_id', $user->id)->update(['status' => 0]);

            $otpLog = OtpLog::create([
                'user_id'   => $user->id,
                'otp'       => $otp,
                'status'    => 1,
                'expires_at' => $expiresAt,
            ]);

            try {

                Mail::to($user->email)->send(new OtpMail($otp));

                MailLog::create([
                    'user_id'       => $user->id,
                    'to_email'      => $user->email,
                    'subject'       => 'Verify Your Account',
                    'send_status'   => 'completed',
                    'data'          => json_encode(['otp' => $otp]),
                    'response'      => 'Mail Sent successfully',
                    'email_sent_at' => now(),
                ]);
            } catch (\Exception $e) {

                MailLog::create([
                    'user_id'       => $user->id,
                    'to_email'      => $user->email,
                    'subject'       => 'Verify Your Account',
                    'send_status'   => 'failed',
                    'data'          => null,
                    'response'      => $e->getMessage(),
                ]);

                DB::commit();

                return [
                    'success' => false,
                    'message' => 'Email sending failed',
                ];
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'OTP sent successfully',
                'data' => [
                    'user_id' => Crypt::encrypt($user->id),
                    'role'    => $user->role
                ]
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Register Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Registration failed'
            ], 500);
        }
    }


    public function otpVerification(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id'  => 'required|string',
            'otp'     => 'required|digits:6',
            'role'     => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'data' => [],
            ], 422);
        }

        DB::beginTransaction();

        try {
            $userId = Crypt::decrypt($request->input('user_id')); // decrypt user_id

            $user = User::find($userId);
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found.'
                ], 404);
            }

            $otp    = $request->input('otp');

            $otpLog = OtpLog::where('user_id', $userId)
                ->where('status', 1)
                ->orderByDesc('id')
                ->first();

            if (!$otpLog) {
                return response()->json([
                    'success' => false,
                    'message' => 'No OTP found. Please request again.',
                ], 404);
            }

            if ($otpLog->expires_at->isPast()) {
                return response()->json([
                    'success' => false,
                    'message' => 'OTP expired. Please request a new one.',
                ], 409);
            }

            if ($otpLog->otp !== $otp) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid OTP. Please try again.',
                ], 401);
            }

            OtpLog::where('user_id', $user->id)->where('status', 1)->update(['status' => 0]);

            if ($user) {
                $user->update([
                    'is_verified' => 1,
                    'status' => 1,
                    'user_name' => $user->email
                ]);
            }

            $userId = Crypt::encrypt($user->id);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'OTP verified successfully.',
                'data' => [
                    'userID' => $userId,
                    'role' => $request->input('role')
                ]
            ], 200);
        } catch (\Exception $e) {
            Log::error('OTP verification Error: ' . $e->getMessage());
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'OTP verification failed.'
            ], 500);
        }
    }
}
