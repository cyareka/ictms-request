<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;  // <-- Make sure this line is included
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\SendLoginOtp;
use App\Models\User;

class OtpVerificationController extends Controller
{
    public function showVerifyForm()
    {
        return inertia('Auth/VerifyOtp');
    }

    public function verifyOtp(Request $request)
    {
        $request->validate(['otp' => 'required']);

        if ($request->otp == Session::get('login_otp')) {
            // Ensure Auth facade is correctly imported and used here
            Auth::loginUsingId(Session::get('login_user_id'));

            Session::forget('login_otp');
            Session::forget('login_user_id');

            return redirect()->intended('/dashboard');
        }

        return back()->withErrors(['otp' => 'Invalid OTP.']);
    }

    public function resendOtp(Request $request)
    {
        $userId = Session::get('login_user_id');
        $user = User::find($userId);

        if ($user) {
            // Generate a new OTP
            $otp = rand(100000, 999999);

            // Update the OTP in the session
            Session::put('login_otp', $otp);

            // Send the OTP via email
            Mail::to($user->email)->send(new SendLoginOtp($otp));

            return response()->json(['message' => 'OTP resent successfully!']);
        }

        return response()->json(['message' => 'User not found.'], 404);
    }
}
