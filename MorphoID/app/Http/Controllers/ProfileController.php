<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use App\Models\Admin;
use App\Models\LoginLog;
use Carbon\Carbon;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::user();
        $guard = Auth::guard('admin')->check() ? 'admin' : 'web';
        $role = Auth::guard('admin')->check() ? 'System Administrator' : strtoupper($user->role === 'Pelajar' ? 'User' : ($user->role ?? 'User'));
        
        return view('profile', compact('user', 'guard', 'role'));
    }

    public function update(Request $request)
    {
        $user = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::user();
        $isAdmin = Auth::guard('admin')->check();

        $rules = [
            'name' => 'required|string|max:255',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:5120',
            'userid' => 'required|string|unique:' . ($isAdmin ? 'admins' : 'users') . ',userid,' . $user->id,
            'email' => 'required|email|unique:' . ($isAdmin ? 'admins' : 'users') . ',email,' . $user->id,
        ];

        if (!$isAdmin) {
            $rules['institusi'] = 'nullable|string|max:255';
        }

        $request->validate($rules);

        $emailChanged = $user->email !== $request->email;
        $icChanged = $user->userid !== $request->userid;

        $user->name = $request->name;
        $user->userid = $request->userid;
        
        if (!$isAdmin) {
            $user->institusi = $request->institusi;
        }

        // Handle Avatar Upload to Supabase
        if ($request->hasFile('avatar')) {
            $supabaseUrl = env('SUPABASE_URL');
            $supabaseKey = env('SUPABASE_KEY');
            $cleanBaseUrl = str_replace('/rest/v1', '', rtrim($supabaseUrl, '/'));
            
            $file = $request->file('avatar');
            $fileName = time() . '_avatar_' . uniqid() . '_' . str_replace(' ', '_', $file->getClientOriginalName());
            $safeFileName = rawurlencode($fileName);
            $endpoint = $cleanBaseUrl . '/storage/v1/object/natey/uploads/' . $safeFileName;

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $supabaseKey,
                'apikey'        => $supabaseKey,
                'Content-Type'  => $file->getMimeType(),
            ])->withBody(file_get_contents($file), $file->getMimeType())->post($endpoint);

            if ($response->failed()) {
                return back()->with('error', 'Failed to upload profile picture: ' . $response->body());
            }

            $user->avatar = $cleanBaseUrl . '/storage/v1/object/public/natey/uploads/' . $safeFileName;
        }

        // If email changed, require verification
        if ($emailChanged) {
            $user->email = $request->email;
            $user->email_verified_at = null;
            $user->save();

            // Send Verification Email
            $hash = sha1($user->email);
            $role = $isAdmin ? 'Administrator' : 'User';
            $verifyLink = url('/verify-email/' . $user->id . '/' . $hash . '/' . $role);
            
            \Illuminate\Support\Facades\Mail::send('auth.profile_email_update', ['Name' => $user->name, 'link' => $verifyLink], function($message) use ($user) {
                $message->to($user->email)->subject('Action Required: Verify Your New Email Address');
                $message->replyTo(env('MAIL_FROM_ADDRESS', 'morpho.id.fsm@gmail.com'), env('MAIL_FROM_NAME', 'MorphoID'));
            });

            // Logout user
            Auth::guard($isAdmin ? 'admin' : 'web')->logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect('/login')->with('success', 'Profile updated! You have changed your email address. Please check your new email to verify it before logging in.');
        }

        // If IC changed, send notification to current email
        if ($icChanged && !$emailChanged) {
            \Illuminate\Support\Facades\Mail::send('auth.profile_ic_update', ['Name' => $user->name, 'userid' => $user->userid], function($message) use ($user) {
                $message->to($user->email)->subject('Security Alert: User ID (IC) Updated');
                $message->replyTo(env('MAIL_FROM_ADDRESS', 'morpho.id.fsm@gmail.com'), env('MAIL_FROM_NAME', 'MorphoID'));
            });
        }

        $user->save();
        LoginLog::create(['userid' => $user->userid, 'name' => $user->name, 'email' => $user->email, 'role' => $isAdmin ? 'Administrator' : 'User', 'status' => 'Success', 'note' => 'Account Updated', 'created_at' => Carbon::now()]);
        return back()->with('success', 'Profile updated successfully!');
    }

    public function destroy(Request $request)
    {
        $user = Auth::guard('admin')->check() ? Auth::guard('admin')->user() : Auth::user();
        $isAdmin = Auth::guard('admin')->check();

        // Delete user from DB (Supabase via Eloquent)
        LoginLog::create(['userid' => $user->userid, 'name' => $user->name, 'email' => $user->email, 'role' => $isAdmin ? 'Administrator' : 'User', 'status' => 'Success', 'note' => 'Account Deleted', 'created_at' => Carbon::now()]);
        $user->delete();

        // Logout
        Auth::guard($isAdmin ? 'admin' : 'web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/intro')->with('success', 'Your account has been permanently deleted.');
    }
}


