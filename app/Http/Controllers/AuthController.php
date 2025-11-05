<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\RegistrationOtp;
use App\Mail\ForgotPasswordOtp;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegisterForm()
    {
        return view('auth.register');
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            return redirect()->intended(route('applicant.create'));
        }

        return redirect()->back()
            ->withErrors(['email' => 'Email atau password salah.'])
            ->withInput();
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => [
                'required',
                'string',
                'min:8', // must be at least 8 characters
                'confirmed',
                'regex:/[a-z]/',      // must contain at least one lowercase letter
                'regex:/[A-Z]/',      // must contain at least one uppercase letter
                'regex:/[0-9]/',      // must contain at least one digit
                'regex:/[@$!%*?&#^()\[\]{}<>~`_+=\\|;:\"\'\/,.-]/' // at least one special char
            ],
            'accepted_terms' => 'accepted',
        ], [
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial.',
            'password.min' => 'Password harus setidaknya 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        // Generate OTP and store pending registration in session
        $otp = rand(100000, 999999);
        $pending = [
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'accepted_terms_at' => now(),
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ];

        session(['pending_registration' => $pending]);

        // send OTP email
        try {
            Mail::to($request->email)->send(new RegistrationOtp($otp));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['email' => 'Gagal mengirim OTP ke email. Pastikan email valid atau coba lagi.'])->withInput();
        }

        return redirect()->route('register.verify')->with('success', 'Kode OTP telah dikirim ke email Anda. Masukkan kode untuk menyelesaikan pendaftaran.');
    }

    public function showVerifyForm(Request $request)
    {
        if (!session('pending_registration')) {
            return redirect()->route('register');
        }
        return view('auth.verify');
    }

    public function verifyRegister(Request $request)
    {
        $pending = session('pending_registration');
        if (!$pending) {
            return redirect()->route('register')->withErrors(['email' => 'Tidak ada pendaftaran tertunda. Silakan daftar lagi.']);
        }

        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if (now()->greaterThan($pending['otp_expires_at'])) {
            session()->forget('pending_registration');
            return redirect()->route('register')->withErrors(['email' => 'Kode OTP sudah kadaluarsa. Silakan daftar ulang.']);
        }

        if ($request->otp != $pending['otp']) {
            return redirect()->back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        // create user
        $user = User::create([
            'name' => $pending['name'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'accepted_terms_at' => $pending['accepted_terms_at'],
        ]);

        // clear pending
        session()->forget('pending_registration');

        return redirect()->route('login')->with('success', 'Akun berhasil dibuat! Silakan login.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot_password');
    }

    public function forgotPassword(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'Email tidak terdaftar dalam sistem.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::where('email', $request->email)->first();

        // Generate OTP and store in session
        $otp = rand(100000, 999999);
        $pending = [
            'email' => $request->email,
            'user_id' => $user->id,
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes(10),
        ];

        session(['pending_password_reset' => $pending]);

        // Send OTP email
        try {
            Mail::to($request->email)->send(new ForgotPasswordOtp($otp, $user->name));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['email' => 'Gagal mengirim OTP ke email. Pastikan email valid atau coba lagi.'])->withInput();
        }

        return redirect()->route('password.verify')->with('success', 'Kode OTP telah dikirim ke email Anda. Masukkan kode untuk melanjutkan reset password.');
    }

    public function showVerifyPasswordForm()
    {
        if (!session('pending_password_reset')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify_password');
    }

    public function verifyPasswordOtp(Request $request)
    {
        $pending = session('pending_password_reset');
        if (!$pending) {
            return redirect()->route('password.request')->withErrors(['email' => 'Tidak ada permintaan reset password. Silakan coba lagi.']);
        }

        $validator = Validator::make($request->all(), [
            'otp' => 'required|digits:6',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if (now()->greaterThan($pending['otp_expires_at'])) {
            session()->forget('pending_password_reset');
            return redirect()->route('password.request')->withErrors(['email' => 'Kode OTP sudah kadaluarsa. Silakan minta kode baru.']);
        }

        if ($request->otp != $pending['otp']) {
            return redirect()->back()->withErrors(['otp' => 'Kode OTP salah.']);
        }

        // OTP verified, redirect to reset password form
        return redirect()->route('password.reset');
    }

    public function showResetPasswordForm()
    {
        if (!session('pending_password_reset')) {
            return redirect()->route('password.request');
        }
        return view('auth.reset_password');
    }

    public function resetPassword(Request $request)
    {
        $pending = session('pending_password_reset');
        if (!$pending) {
            return redirect()->route('password.request')->withErrors(['email' => 'Sesi reset password telah berakhir. Silakan coba lagi.']);
        }

        $validator = Validator::make($request->all(), [
            'password' => [
                'required',
                'string',
                'min:8',
                'confirmed',
                'regex:/[a-z]/',
                'regex:/[A-Z]/',
                'regex:/[0-9]/',
                'regex:/[@$!%*?&#^()\[\]{}<>~`_+=\\|;:\"\'\/,.-]/'
            ],
        ], [
            'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, angka, dan karakter spesial.',
            'password.min' => 'Password harus setidaknya 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok dengan password.',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator);
        }

        // Update user password
        $user = User::find($pending['user_id']);
        if (!$user) {
            session()->forget('pending_password_reset');
            return redirect()->route('password.request')->withErrors(['email' => 'User tidak ditemukan.']);
        }

        $user->password = Hash::make($request->password);
        $user->save();

        // Clear session
        session()->forget('pending_password_reset');

        return redirect()->route('login')->with('success', 'Password berhasil direset! Silakan login dengan password baru Anda.');
    }
}