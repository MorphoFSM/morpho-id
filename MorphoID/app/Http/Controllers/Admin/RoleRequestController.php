<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RoleRequest;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginLog;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class RoleRequestController extends Controller
{
    public function store(Request $request)
    {
        // Must be a normal user to request
        if (!Auth::check() || Auth::guard('admin')->check()) {
            return response()->json(['success' => false, 'message' => 'Only normal users can request a role change.']);
        }

        $user = Auth::user();
        
        // Check if there is already a pending request
        $existing = RoleRequest::where('user_id', $user->id)->where('status', 'pending')->first();
        if ($existing) {
            return response()->json(['success' => false, 'message' => 'You already have a pending request.']);
        }

        RoleRequest::create([
            'user_id' => $user->id,
            'requested_role' => 'admin',
            'status' => 'pending'
        ]);

        return response()->json(['success' => true, 'message' => 'Request message has been sent to Admin.']);
    }

    public function index()
    {
        $requests = RoleRequest::with('user')->where('status', 'pending')->get();
        $pendingAdmins = Admin::where('admin_key', 'PENDING_APPROVAL')->get();
        return view('admin.role_requests', compact('requests', 'pendingAdmins'));
    }

    public function approvePendingAdmin($id)
    {
        try {
            $admin = Admin::where('id', $id)->where('admin_key', 'PENDING_APPROVAL')->firstOrFail();

            $hash = sha1($admin->email);
            $verifyLink = url('/verify-email/' . $admin->id . '/' . $hash . '/Administrator');

            // 1. Cuba hantar email dulu
            Mail::send('auth.verify_admin_email', ['Name' => $admin->name, 'link' => $verifyLink], function($message) use ($admin) {
                $message->to($admin->email)->subject('Action Required: Verify Your Administrator Account');
                $message->replyTo(env('MAIL_FROM_ADDRESS', 'morpho.id.fsm@gmail.com'), env('MAIL_FROM_NAME', 'MorphoID'));
            });

            // 2. Kalau berjaya hantar email tanpa sangkut, baru save database
            $admin->admin_key = null; // Clear pending flag
            $admin->save();

            LoginLog::create(['userid' => $admin->userid, 'name' => $admin->name, 'email' => $admin->email, 'role' => 'Administrator', 'status' => 'Success', 'note' => 'New Admin Approved', 'created_at' => Carbon::now()]);

            return back()->with('success', 'New administrator has been approved! A verification email has been sent to them.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error approving admin: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    public function rejectPendingAdmin($id)
    {
        try {
            $admin = Admin::where('id', $id)->where('admin_key', 'PENDING_APPROVAL')->firstOrFail();

            Mail::send('auth.reject_admin_email', ['Name' => $admin->name], function($message) use ($admin) {
                $message->to($admin->email)->subject('Update on Your Administrator Registration');
                $message->replyTo(env('MAIL_FROM_ADDRESS', 'morpho.id.fsm@gmail.com'), env('MAIL_FROM_NAME', 'MorphoID'));
            });

            LoginLog::create(['userid' => $admin->userid, 'name' => $admin->name, 'email' => $admin->email, 'role' => 'Administrator', 'status' => 'Failed', 'note' => 'Admin Registration Rejected', 'created_at' => Carbon::now()]);

            $admin->delete();

            return back()->with('success', 'New administrator registration has been rejected and deleted.');
        } catch (\Throwable $e) {
            return back()->with('error', 'Error rejecting admin: ' . $e->getMessage() . ' at ' . $e->getFile() . ':' . $e->getLine());
        }
    }

    public function approve($id)
    {
        $roleRequest = RoleRequest::findOrFail($id);
        
        if ($roleRequest->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        $user = $roleRequest->user;
        
        // Check if admin already exists with this userid or email
        $admin = Admin::where('userid', $user->userid)->orWhere('email', $user->email)->first();
        
        if (!$admin) {
            $admin = Admin::create([
                'name' => $user->name,
                'email' => $user->email,
                'userid' => $user->userid,
                'institusi' => $user->institusi,
                'password' => $user->password,
                'email_verified_at' => null, // Unverified initially
                'avatar' => $user->avatar,
                'admin_key' => null // Key generated upon verification
            ]);
        } else {
            // Sync the existing admin record with the user's current data
            $admin->name = $user->name;
            $admin->institusi = $user->institusi;
            $admin->password = $user->password;
            $admin->avatar = $user->avatar;
            $admin->email_verified_at = null; // Require verification again
            $admin->admin_key = null; // Clear key until verified
            $admin->save();
        }
        
        // Move the user data by deleting the old user record
        $user->delete();

        // 1. Cuba hantar email dulu
        $hash = sha1($admin->email);
        $verifyLink = url('/verify-email/' . $admin->id . '/' . $hash . '/Administrator');

        Mail::send('auth.verify_admin_email', ['Name' => $admin->name, 'link' => $verifyLink], function($message) use ($admin) {
            $message->to($admin->email)->subject('Action Required: Verify Your Administrator Upgrade');
            $message->replyTo(env('MAIL_FROM_ADDRESS', 'morpho.id.fsm@gmail.com'), env('MAIL_FROM_NAME', 'MorphoID'));
        });

        LoginLog::create(['userid' => $admin->userid, 'name' => $admin->name, 'email' => $admin->email, 'role' => 'Administrator', 'status' => 'Success', 'note' => 'Role Upgraded to Admin', 'created_at' => Carbon::now()]);

        $roleRequest->status = 'approved';
        $roleRequest->save();

        return back()->with('success', "User '{$admin->name}' has been successfully upgraded to Administrator! A verification email has been sent to them.");
    }

    public function reject($id)
    {
        $roleRequest = RoleRequest::findOrFail($id);
        
        if ($roleRequest->status !== 'pending') {
            return back()->with('error', 'Request already processed.');
        }

        $roleRequest->status = 'rejected';
        $roleRequest->save();

        $user = User::find($roleRequest->user_id);
        if ($user) {
            LoginLog::create(['userid' => $user->userid, 'name' => $user->name, 'email' => $user->email, 'role' => 'User', 'status' => 'Success', 'note' => 'Rejected as Admin', 'created_at' => Carbon::now()]);
        }

        return back()->with('success', 'Role request rejected.');
    }
}


