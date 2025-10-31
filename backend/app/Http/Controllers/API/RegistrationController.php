<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PendingRegistration;
use App\Mail\VerificationCodeMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Carbon\Carbon;

class RegistrationController extends Controller
{
    public function sendCode(Request $request)
    {
        $data = $request->validate([
            'fname' => 'required|string|max:255',
            'lname' => 'required|string|max:255',
            'phone' => 'required|string|max:15',
            'email' => 'required|email|unique:pending_registrations,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (User::where('email', $data['email'])->exists()) {
            return response()->json(['message' => 'User with this email already exists.'], 422);
        }

        $code = rand(100000, 999999);

        $hashedPassword = Hash::make($data['password']);

        $expiresAt = Carbon::now()->addMinutes(15);
        $pending = PendingRegistration::updateOrCreate(
            ['email' => $data['email']],
            [
                'fname' => $data['fname'],
                'lname' => $data['lname'],
                'phone' => $data['phone'],
                'password' => $hashedPassword,
                'code' => $code,
                'expires_at' => $expiresAt,
            ]
            );

            Mail::to($data['email'])->send(new VerificationCodeMail($data['fname'], $code));

            return response()->json([
                'message' => 'Verification code sent to your email.'
            ], 200);
    }

    public function verifyCode(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email',
            'code' => 'required|string|size:6',
        ]);

        $pending = PendingRegistration::where('email', $data['email'])->first();

        if (! $pending) {
            return response()->json(['message' => 'No pending registration found for this email.'], 404);
        }

        if ($pending->expires_at && Carbon::now()->gt(Carbon::parse($pending->expires_at))) {
            $pending->delete();
            return response()->json(['message' => 'Verification code has expired. Please register again.'], 410);
        }

        if (! hash_equals($pending->code, $data['code'])) {
            return response() ->json(['message' => 'Invalid verification code.'], 422);
        }

        $user = User::create([
            'name' => trim($pending->fname . ' ' . $pending->lname),
            'phone' => $pending->phone,
            'email' => $pending->email,
            'password' => $pending->password,
        ]);

        $pending->delete();

        return response()->json([
            'message' => 'Registration completed successfully.'
        ], 201);
    }
}
