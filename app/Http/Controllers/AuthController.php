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
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;
use App\Models\Applicant;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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

        // Exclude soft-deleted users if is_delete column exists
        if (Schema::hasColumn('users', 'is_delete')) {
            $credentials['is_delete'] = 0;
        }

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
            'date_of_birth' => 'required|date',
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

        // enforce age >= 18 on server-side
        try {
            $dob = Carbon::parse($request->input('date_of_birth'));
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['date_of_birth' => 'Format tanggal lahir tidak valid.'])->withInput();
        }

        if ($dob->age < 18) {
            return redirect()->back()->withErrors(['date_of_birth' => 'Umur minimal 18 tahun untuk mendaftar.'])->withInput();
        }

        // Generate OTP and store pending registration in session
        $otp = rand(100000, 999999);
        $pending = [
            'name' => $request->name,
            'email' => $request->email,
            'date_of_birth' => $request->date_of_birth,
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
        $userData = [
            'name' => $pending['name'],
            'email' => $pending['email'],
            'password' => $pending['password'],
            'accepted_terms_at' => $pending['accepted_terms_at'],
        ];

        // Only include date_of_birth if the column exists in the DB (migration may not have been run)
        if (isset($pending['date_of_birth']) && Schema::hasColumn('users', 'date_of_birth')) {
            $userData['date_of_birth'] = $pending['date_of_birth'];
        }

        $user = User::create($userData);

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

    /**
     * Show account deletion confirmation form to the authenticated user.
     */
    public function showDeleteAccountForm()
    {
        return view('auth.delete_account');
    }

    /**
     * Delete the authenticated user's account after password confirmation.
     */
    public function deleteAccount(Request $request)
    {
        $user = Auth::user();
        if (! $user) {
            return redirect('/')->withErrors(['account' => 'User tidak ditemukan.']);
        }

        $validator = Validator::make($request->all(), [
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator);
        }

        if (! Hash::check($request->input('password'), $user->password)) {
            return redirect()->back()->withErrors(['password' => 'Password tidak cocok.']);
        }

        // Perform deletion inside a transaction
        $userId = $user->id;
        $userEmail = $user->email;
        DB::transaction(function() use ($user, $userId, $userEmail) {
            // Delete all ApplyJob records for this user first
            try {
                \App\Models\ApplyJob::where('user_id', $user->id)->delete();
            } catch (\Exception $e) {
                \Log::warning('Failed to delete ApplyJob records during account deletion', [
                    'user_id' => $userId,
                    'error' => $e->getMessage()
                ]);
            }

            // Delete applicant and related records if any
            $applicant = Applicant::where('user_id', $user->id)->first();
            if ($applicant) {
                // delete related education/work/training records if relationships exist
                try {
                    if (method_exists($applicant, 'workExperiences')) {
                        $applicant->workExperiences()->delete();
                    }
                    if (method_exists($applicant, 'educations')) {
                        $applicant->educations()->delete();
                    }
                    if (method_exists($applicant, 'trainings')) {
                        $applicant->trainings()->delete();
                    }
                } catch (\Exception $e) {
                    // ignore individual relationship deletion errors and continue
                }

                // Delete stored files (cv, photo, idcard) if present and disk exists
                try {
                    $disk = Storage::disk('mlnas');
                    foreach (['cvpath','photopath','idcardpath'] as $p) {
                        if (!empty($applicant->{$p}) && $disk->exists($applicant->{$p})) {
                            $disk->delete($applicant->{$p});
                        }
                    }
                } catch (\Exception $e) {
                    // ignore storage cleanup errors
                }

                // delete applicant record
                try { $applicant->delete(); } catch (\Exception $e) {}
            }

                // remove any password reset tokens for this email
                try { 
                    \Illuminate\Support\Facades\DB::table('password_resets')->where('email', $userEmail)->delete();
                } catch (\Exception $e) {}

                // finally delete the user
                try { \App\Models\User::destroy($userId); } catch (\Exception $e) {}
        });

        // Logout and clear session completely
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        // Clear any remember me tokens
        try {
            DB::table('sessions')->where('user_id', $userId)->delete();
        } catch (\Exception $e) {
            Log::warning('Failed to clear session records during account deletion', [
                'user_id' => $userId,
                'error' => $e->getMessage()
            ]);
        }

        // Verify deletion - if user still exists, notify admin and return error
        if (\App\Models\User::find($userId)) {
            \Log::warning('Account deletion attempted but user record still exists', ['user_id' => $userId, 'email' => $userEmail]);
            return redirect('/')->with('warning', 'Permintaan hapus akun diproses, tetapi beberapa data tidak dapat dihapus sepenuhnya. Silakan hubungi admin.');
        }

        return redirect('/')->with('success', 'Akun Anda berhasil dihapus.');
    }
}