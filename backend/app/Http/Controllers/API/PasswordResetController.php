<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Models\User;
use App\Mail\PasswordResetMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;
use App\Mail\PasswordResetConfirmationMail;

class PasswordResetController extends Controller
{
    /**
     * Step 1:Send a password reset verification code to the email.
     */
    public function sendResetCode(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
        ]);

        //Check if user exists
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'Email address provided does not exist in our records.',
            ], 404);
        }

        //Generate a unique 6-digit code
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        //Set expiration to 10 minutes from now
        $expiresAt = Carbon::now()->addMinutes(10);

        // Update or create password reset record
        PasswordReset::updateOrCreate(
            ['email' => $data['email']],
            [
                'code' => $code, 'expires_at' => $expiresAt,
            ]
        );

        // Send email with verification code
        Mail::to($data['email'])->send(new PasswordResetMail($user->name, $code));

        return response()->json([
            'message' => 'Verification code sent to your email.'
        ], 200);
    }

    /**
     * Step 2: Verify the code.
     */
    public function verifyCode(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        // Find the password reset record
        $reset = PasswordReset::where('email', $data['email'])->first();

        if (!$reset) {
            return response()->json([
                'message' => 'No password reset request found for this email.'
            ], 404);
        }

        // Check if code has expired
        if (Carbon::now()->gt(Carbon::parse($reset->expires_at))) {
            $reset->delete();
            return response()->json([
                'message' => 'Verification code has expired. Please request a new one.'
            ], 410);
        }

        // Verify the code using hash_equals for security
        if (!hash_equals($reset->code, $data['code'])) {
            return response()->json([
                'message' => 'Invalid verification code.'
            ], 422);
        }

        return response()->json([
            'message' => 'Code successfully verified. You can now reset your password.'
        ], 200);
    }

    /**
     * Step 3: Reset the password.
     */
    public function resetPassword(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Find the password reset record
        $reset = PasswordReset::where('email', $data['email'])->first();

        if (!$reset) {
            return response()->json([
                'message' => 'No password reset request found for this email.'
            ], 404);
        }

        // Check if code has expired
        if (Carbon::now()->gt(Carbon::parse($reset->expires_at))) {
            $reset->delete();
            return response()->json([
                'message' => 'Verification code has expired. Please request a new one.'
            ], 410);
        }

        // Verify the code
        if (!hash_equals($reset->code, $data['code'])) {
            return response()->json([
                'message' => 'Invalid verification code.'
            ], 422);
        }

        // Find user and update password
        $user = User::where('email', $data['email'])->first();

        if (!$user) {
            return response()->json([
                'message' => 'User not found.'
            ], 404);
        }

        // Update the user's password
        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        // Delete the password reset record
        $reset->delete();

        // Send confirmation email
        Mail::to($user->email)->send(new PasswordResetConfirmationMail($user->name));

        return response()->json([
            'message' => 'Password has been reset successfully. You can now log in with your new password.'
        ], 200);
    }
}
