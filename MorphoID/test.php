<?php

use App\Models\Admin;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

try {
    $admin = Admin::where('id', 32)->firstOrFail();
    $admin->admin_key = null;
    
    $hash = sha1($admin->email);
    $verifyLink = url('/verify-email/' . $admin->id . '/' . $hash . '/Administrator');

    Mail::send('auth.verify_admin_email', ['Name' => $admin->name, 'link' => $verifyLink], function($message) use ($admin) {
        $message->to($admin->email)->subject('Action Required: Verify Your Administrator Account');
        $message->replyTo(env('MAIL_FROM_ADDRESS', 'morpho.id.fsm@gmail.com'), env('MAIL_FROM_NAME', 'MorphoID'));
    });

    echo "Success";
} catch (\Throwable $e) {
    echo "ERROR: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine();
}
