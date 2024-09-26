<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\OTPMail;


class AuthController extends Controller
{
    // Show the registration form to the user
    public function showRegistrationForm()
    {
        return view('auth.register'); // Returns the registration view
    }

    // Handle user registration
    public function register(Request $request)
    {
        // Validate the registration form data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users', // Ensure the email is unique
            'password' => 'required|string|min:8|confirmed', // Password must be confirmed
        ]);

        // Create a new user with the provided data
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), // Hash the password before saving
        ]);

        // Generate and send OTP to the user's email
        $this->sendOTP($user);

        // Store the user's email in the session for later verification
        session(['email' => $user->email]);

        // Redirect to the OTP verification page with a success message
        return redirect()->route('verify')->with('success', 'We have sent an OTP to your email. Please enter it below.');
    }

    // Generate and send OTP to the user's email
    protected function sendOTP(User $user)
    {
        $otp = rand(100000, 999999); // Generate a 6-digit random OTP
        $user->otp = $otp;
        $user->otp_expires_at = Carbon::now()->addMinutes(10); // OTP expires in 10 minutes
        $user->save(); // Save the OTP and expiration time to the user record

        // Send the OTP to the user's email
        Mail::to($user->email)->send(new OTPMail($otp));
    }

    // Show the OTP verification form
    public function showOTPForm()
    {
        if (!session('email')) {
            return redirect()->route('register'); // Redirect to register if no email in session
        }

        return view('auth.verify'); // Return the OTP verification view
    }

    // Handle OTP verification
    public function verifyOTP(Request $request)
    {
        // Validate the OTP input
        $request->validate([
            'otp' => 'required|string|min:6|max:6', // OTP must be 6 digits
        ]);

        // Find the user based on the email stored in session
        $user = User::where('email', session('email'))->first();

        // Check if the OTP is correct and not expired
        if ($user && $user->otp === $request->otp && Carbon::now()->lessThanOrEqualTo($user->otp_expires_at)) {
            // Clear OTP data, mark the email as verified, and set the OTP as verified
            $user->otp = null;
            $user->otp_expires_at = null;
            $user->email_verified_at = now();
            $user->is_otp_verified = true;
            $user->save();

            session()->forget('email'); // Remove the email from session

            // Redirect to the login page with a success message
            return redirect()->route('login')->with('success', 'Your email has been verified. Please log in.');
        }

        // If OTP is invalid or expired, redirect back with an error message
        return back()->withErrors(['otp' => 'Invalid or expired OTP.']);
    }

    // Handle OTP resend functionality
    public function resendOTP(Request $request)
    {
        // Validate the email input
        $request->validate([
            'email' => 'required|email|exists:users,email', // Ensure the email exists in the users table
        ]);

        // Find the user based on the provided email
        $user = User::where('email', $request->email)->first();

        // If the user exists and the email is not yet verified
        if ($user && !$user->email_verified_at) {
            // Generate and send a new OTP
            $this->sendOTP($user);

            // Redirect to the OTP verification page with a success message
            return redirect()->route('verify')->with('success', 'A new OTP has been sent to your email. Please enter it below.');
        }

        // If the email is already verified or doesn't exist, return an error
        return back()->withErrors(['email' => 'Email is already verified or does not exist.']);
    }

    // Show the login form to the user
    public function showLoginForm()
    {
        return view('auth.login'); // Returns the login view
    }

    // Handle user login
    public function login(Request $request)
    {
        // Validate the login form data
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // Find the user based on the provided email
        $user = User::where('email', $request->email)->first();

        // Check if the user exists and the password is correct
        if ($user && Hash::check($request->password, $user->password)) {
            // If the user has not verified their OTP, redirect them to the OTP verification page
            if (!$user->is_otp_verified) {
                return redirect()->route('verify.otp')->withErrors(['email' => 'You need to verify your email using the OTP sent to you.']);
            }

            // Log the user in and redirect to the dashboard
            Auth::login($user);

            return redirect()->route('dashboard');
        }

        // If the credentials don't match, redirect back with an error
        return back()->withErrors(['email' => 'The provided credentials do not match our records.']);
    }

    // Show the user dashboard
    public function showDashboard()
    {
        return view('dashboard', ['user' => Auth::user()]); // Pass the authenticated user to the dashboard view
    }

    // Handle user logout
    public function logout(Request $request)
    {
        Auth::logout(); // Log the user out

        $request->session()->invalidate(); // Invalidate the session
        $request->session()->regenerateToken(); // Regenerate the CSRF token

        return redirect()->route('login'); // Redirect to the login page
    }
}
