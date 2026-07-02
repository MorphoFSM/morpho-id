<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function showForgotPasswordForm()
    {
        return view('auth.forgot-password');
    }

    // ==========================================
    // 1. PROSES PENDAFTARAN
    // ==========================================
    public function register_action(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => [
                'required', 'email', 'max:255',
                function ($attribute, $value, $fail) {
                    if (\App\Models\User::where('email', $value)->exists() || \App\Models\Admin::where('email', $value)->exists()) {
                        $fail('This email is already registered in the system. Please use a different email.');
                    }
                },
            ],
            'userid' => [
                'required', 'string', 'max:12',
                function ($attribute, $value, $fail) {
                    if (\App\Models\User::where('userid', $value)->exists() || \App\Models\Admin::where('userid', $value)->exists()) {
                        $fail('This ID/IC is already registered in the system. Please use a different ID.');
                    }
                },
            ],
            'institusi' => 'required|string|max:255',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:User,Administrator'
        ], ['password.confirmed' => 'Kata laluan pengesahan tidak sepadan.']);

        if ($validator->fails()) {
            LoginLog::create([
                'userid' => $request->userid ?? 'Unknown',
                'name' => $request->name ?? 'Unknown',
                'email' => $request->email ?? 'Unknown',
                'role' => $request->role ?? 'Unknown',
                'status' => 'Failed',
                'note' => 'Register failed: ' . implode(', ', $validator->errors()->all()),
                'created_at' => Carbon::now()
            ]);
            $isApi = $request->wantsJson() || $request->is('api/*');
            if ($isApi) {
                return response()->json(['status' => 'error', 'message' => $validator->errors()->first()], 400);
            }
            return back()->withErrors($validator)->withInput();
        }

        $isApi = $request->wantsJson() || $request->is('api/*');

        if ($request->role === 'Administrator') {
            $validatorAdmin = Validator::make($request->all(), ['userid' => 'unique:admins,userid', 'email' => 'unique:admins,email']);
            if ($validatorAdmin->fails()) {
                LoginLog::create([
                    'userid' => $request->userid, 'name' => $request->name, 'email' => $request->email, 'role' => 'Administrator',
                    'status' => 'Failed', 'note' => 'Register failed: ' . implode(', ', $validatorAdmin->errors()->all()), 'created_at' => Carbon::now()
                ]);
                if ($isApi) {
                    return response()->json(['status' => 'error', 'message' => $validatorAdmin->errors()->first()], 400);
                }
                return back()->withErrors($validatorAdmin)->withInput();
            }

            $admin = Admin::create([
                'name' => $request->name,
                'email' => $request->email,
                'userid' => $request->userid,
                'institusi' => $request->institusi,
                'password' => Hash::make($request->password),
                'admin_key' => 'PENDING_APPROVAL'
            ]);

            LoginLog::create([
                'userid' => $admin->userid, 'name' => $admin->name, 'email' => $admin->email, 'role' => 'Administrator',
                'status' => 'Success', 'note' => 'Registration Pending Approval', 'created_at' => Carbon::now()
            ]);

            if ($isApi) {
                return response()->json(['status' => 'success', 'message' => 'Admin registration submitted! Please wait for approval.'], 201);
            }
            return redirect('/login')->with('success', 'Admin registration submitted! You will receive an email once your account is approved by an existing Administrator.');

        } else {
            $validatorUser = Validator::make($request->all(), ['userid' => 'unique:users,userid', 'email' => 'unique:users,email']);
            if ($validatorUser->fails()) {
                LoginLog::create([
                    'userid' => $request->userid, 'name' => $request->name, 'email' => $request->email, 'role' => 'User',
                    'status' => 'Failed', 'note' => 'Register failed: ' . implode(', ', $validatorUser->errors()->all()), 'created_at' => Carbon::now()
                ]);
                if ($isApi) {
                    return response()->json(['status' => 'error', 'message' => $validatorUser->errors()->first()], 400);
                }
                return back()->withErrors($validatorUser)->withInput();
            }

            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'userid' => $request->userid,
                'institusi' => $request->institusi,
                'password' => Hash::make($request->password)
            ]);

            LoginLog::create([
                'userid' => $user->userid, 'name' => $user->name, 'email' => $user->email, 'role' => 'User',
                'status' => 'Success', 'note' => 'Account Registered', 'created_at' => Carbon::now()
            ]);

            $hash = sha1($user->email);
            $verifyLink = url('/verify-email/' . $user->id . '/' . $hash . '/User');
            Mail::send('auth.register_success', ['Name' => $user->name, 'peranan' => 'User', 'link' => $verifyLink], function($message) use ($user) {
                $message->to($user->email)->subject('Welcome to MorphoID - Verify Your Email!');
                $message->replyTo(env('MAIL_FROM_ADDRESS', 'morpho.id.fsm@gmail.com'), env('MAIL_FROM_NAME', 'MorphoID'));
            });

            if ($isApi) {
                return response()->json(['status' => 'success', 'message' => 'User account created! Please check your email.'], 201);
            }

            return redirect('/login')->with('success', 'User account created! Please check your email for verification.');
        }
    }

    // ==========================================
    // 2. PROSES LOG MASUK
    // ==========================================
    public function login_action(Request $request)
    {
        $isApi = $request->wantsJson() || $request->is('api/*');
        $role = $request->role ?? 'User';

        $validator = Validator::make($request->all(), [
            'userid' => 'required',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            LoginLog::create([
                'userid' => $request->userid ?? 'Unknown', 'name' => 'Unknown', 'email' => 'Unknown',
                'role' => $role, 'status' => 'Failed', 'note' => 'Login failed: ' . implode(', ', $validator->errors()->all()), 'created_at' => Carbon::now()
            ]);
            if ($isApi) {
                return response()->json(['message' => $validator->errors()->first()], 400);
            }
            return back()->withErrors($validator)->withInput();
        }

        if ($role === 'Administrator') {
            $admin = Admin::where('userid', $request->userid)->first();

            if ($admin && Hash::check($request->password, $admin->password)) {
                if ($admin->email_verified_at == null) {
                    LoginLog::create(['userid' => $admin->userid, 'name' => $admin->name, 'email' => $admin->email, 'role' => 'Administrator', 'status' => 'Failed', 'note' => 'Login failed: Email not verified', 'created_at' => Carbon::now()]);
                    return $isApi ? response()->json(['message' => 'Admin account is not verified! Please check your email.'], 403) : back()->with('error', 'Admin account is not verified! Please check your email.');
                }
                
                // Only require admin key if it hasn't been marked as USED
                if ($admin->admin_key !== 'USED') {
                    if (empty($request->admin_key)) {
                        LoginLog::create(['userid' => $admin->userid, 'name' => $admin->name, 'email' => $admin->email, 'role' => 'Administrator', 'status' => 'Failed', 'note' => 'Login failed: Admin Key required for first login', 'created_at' => Carbon::now()]);
                        return $isApi ? response()->json(['message' => 'Please enter the Administration Key for first-time login!'], 403) : back()->with('error', 'Please enter the Administration Key for first-time login!')->withInput();
                    }
                    if ($admin->admin_key !== $request->admin_key) {
                        LoginLog::create(['userid' => $admin->userid, 'name' => $admin->name, 'email' => $admin->email, 'role' => 'Administrator', 'status' => 'Failed', 'note' => 'Login failed: Invalid Administration Key', 'created_at' => Carbon::now()]);
                        return $isApi ? response()->json(['message' => 'Invalid Administration Key!'], 403) : back()->with('error', 'Invalid Administration Key!')->withInput();
                    }
                    // Mark as used after successful validation
                    $admin->admin_key = 'USED';
                    $admin->save();
                }
                Auth::guard('admin')->login($admin);
                LoginLog::create(['userid' => $request->userid, 'name' => $admin->name, 'email' => $admin->email, 'role' => 'Administrator', 'status' => 'Success', 'note' => 'Logged In', 'created_at' => Carbon::now()]);

                return $isApi ? response()->json(['status' => 'success', 'message' => 'Login successful!'], 200) : redirect('/index');
            }
        } else {
            $user = User::where('userid', $request->userid)->first();

            if ($user && Hash::check($request->password, $user->password)) {
                if ($user->email_verified_at == null) {
                    LoginLog::create(['userid' => $user->userid, 'name' => $user->name, 'email' => $user->email, 'role' => 'User', 'status' => 'Failed', 'note' => 'Login failed: Email not verified', 'created_at' => Carbon::now()]);
                    return $isApi ? response()->json(['message' => 'User account is not verified! Please check your email.'], 403) : back()->with('error', 'User account is not verified! Please check your email.');
                }
                Auth::login($user);
                LoginLog::create(['userid' => $request->userid, 'name' => $user->name, 'email' => $user->email, 'role' => 'User', 'status' => 'Success', 'note' => 'Logged In', 'created_at' => Carbon::now()]);

                return $isApi ? response()->json(['status' => 'success', 'message' => 'Login successful!'], 200) : redirect('/index');
            }
        }

        LoginLog::create([
            'userid' => $request->userid ?? 'Unknown', 'name' => 'Unknown', 'email' => 'Unknown',
            'role' => $role, 'status' => 'Failed', 'note' => 'Login failed: Invalid ID or Password', 'created_at' => Carbon::now()
        ]);
        return $isApi ? response()->json(['message' => 'Invalid ID or password.'], 401) : back()->with('error', 'Invalid ID or password.')->withInput();
    }

    // ==========================================
    // 3. PROSES PENGESAHAN E-MEL
    // ==========================================
    public function verify_email($id, $hash, $role)
    {
        if ($role === 'User') {
            $user = User::find($id);
            if ($user && sha1($user->email) === $hash) {
                $user->email_verified_at = Carbon::now();
                $user->save();
                return redirect('/login')->with('success', 'Email verified successfully! Welcome to MorphoID. You can now log in as a User.');
            }
        } elseif ($role === 'Administrator') {
            $admin = Admin::find($id);
            if ($admin && sha1($admin->email) === $hash) {
                $admin->email_verified_at = Carbon::now();
                
                // Generate Admin Key if not exists
                if (is_null($admin->admin_key)) {
                    $admin->admin_key = 'ADM-' . strtoupper(Str::random(6));
                    
                    // Send Email with the new key
                    Mail::send('auth.role_approved_email', ['Name' => $admin->name, 'admin_key' => $admin->admin_key], function($message) use ($admin) {
                        $message->to($admin->email)->subject('Congratulations! Administrator Role Approved & Key Generated');
                        $message->replyTo(env('MAIL_FROM_ADDRESS', 'morpho.id.fsm@gmail.com'), env('MAIL_FROM_NAME', 'MorphoID'));
                    });
                }
                
                $admin->save();
                return redirect('/login')->with('success', 'Administrator authentication complete. Check your email for your unique Administration Key.');
            }
        }
        return redirect('/login')->with('error', 'Invalid or expired verification link.');
    }

    // ==========================================
    // 4. LOGOUT
    // ==========================================
    public function logout(Request $request)
    {
        Auth::guard('admin')->check() ? Auth::guard('admin')->logout() : Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/intro');
    }

    // ==========================================
    // 5. PROSES HANTAR LINK FORGOT PASSWORD
    // ==========================================
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->email;
        $isUser = User::where('email', $email)->first();
        $isAdmin = Admin::where('email', $email)->first();

        if (!$isUser && !$isAdmin) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'This e-mail does not exist in our system.'], 404);
            }
            return back()->withErrors(['email' => 'E-mel ini tidak wujud dalam sistem kami.']);
        }

        $token = Str::random(64);
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $email],
            ['email' => $email, 'token' => $token, 'created_at' => Carbon::now()]
        );

        $resetLink = url('/reset-password?token=' . $token . '&email=' . urlencode($email));

        Mail::send('auth.reset_password_email', ['link' => $resetLink], function($message) use ($email) {
            $message->to($email)
                    ->subject('Reset Your MorphoID Password');
            $message->replyTo(env('MAIL_FROM_ADDRESS', 'morpho.id.fsm@gmail.com'), env('MAIL_FROM_NAME', 'MorphoID'));
        });

        if ($request->expectsJson()) {
            return response()->json(['message' => 'A password reset link has been sent to your e-mail!'], 200);
        }
        return back()->with('status', 'A password reset link has been sent to your email!');
    }

    // ==========================================
    // 6. PAPARKAN BORANG RESET PASSWORD
    // ==========================================
    public function showResetForm(Request $request)
    {
        return view('auth.reset-password', [
            'token' => $request->query('token'),
            'email' => $request->query('email')
        ]);
    }

    // ==========================================
    // 7. PROSES UPDATE PASSWORD BARU
    // ==========================================
    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6|confirmed',
            'token' => 'required'
        ], ['password.confirmed' => 'Password confirmation does not match.']);

        $resetRecord = DB::table('password_reset_tokens')->where('email', $request->email)->where('token', $request->token)->first();

        if (!$resetRecord) {
            return back()->with('error', 'Invalid or expired token.');
        }

        $isUser = User::where('email', $request->email)->first();
        $isAdmin = Admin::where('email', $request->email)->first();
        $telahDiupdate = false;

        if ($isUser) {
            $isUser->password = Hash::make($request->password);
            $isUser->save();
            $telahDiupdate = true;
        }

        if ($isAdmin) {
            $isAdmin->password = Hash::make($request->password);
            $isAdmin->save();
            $telahDiupdate = true;
        }

        if (!$telahDiupdate) {
            return back()->with('error', 'Email not found in the system.');
        }

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();
        return redirect('/login')->with('success', 'Your password has been successfully changed! Please login.');
    }
}







