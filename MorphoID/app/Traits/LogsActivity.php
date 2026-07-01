<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logAction($model, 'Created');
        });

        static::updated(function ($model) {
            self::logAction($model, 'Updated');
        });

        static::deleted(function ($model) {
            self::logAction($model, 'Deleted');
        });
    }

    protected static function logAction($model, $action)
    {
        $admin = Auth::guard('admin')->user() ?? Auth::user();
        $adminName = $admin ? $admin->name : 'System';
        $adminEmail = $admin ? $admin->email : 'system@auto';

        $moduleName = class_basename(get_class($model));
        $itemName = $model->nama_spesimen ?? $model->nama_kategori ?? "ID: {$model->id}";

        AuditLog::create([
            'admin_name' => $adminName,
            'admin_email' => $adminEmail,
            'action' => $action,
            'module' => $moduleName,
            'details' => "{$moduleName} '{$itemName}' was {$action}.",
        ]);
    }
}
