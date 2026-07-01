<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AuditLog;

class AdminAuditController extends Controller
{
    public function index()
    {
        $logs = AuditLog::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.audit', compact('logs'));
    }
}
