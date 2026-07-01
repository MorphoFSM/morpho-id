@extends('emails.layout')

@section('title', 'Morpho.ID Notification')

@section('content')
<div style="max-width: 650px; margin: 0 auto; background: #2d3748; padding: 45px; border-radius: 12px; border-top: 5px solid #ed8936; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
        
        <div style="text-align: left; margin-bottom: 35px; border-bottom: 2px solid #4a5568; padding-bottom: 20px;">
            <h2 style="color: #fbd38d; font-size: 24px; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px;">Security Alert: Administration Key Generated</h2>
            <p style="color: #a0aec0; font-size: 14px; font-weight: 600;">Morpho.ID Biological Exploration Repository System</p>
        </div>

        <p style="font-size: 16px;">Dear <strong>{{ $Name }}</strong>,</p>
        
        <p style="font-size: 16px;">
            Your email has been successfully verified. As an Administrator, your account requires a secondary layer of authentication. The system has automatically generated a unique and highly secure <strong>Administration Key</strong> specifically for your account.
        </p>

        <div style="background-color: #1a202c; border: 1px solid #4a5568; padding: 25px; margin: 30px 0; text-align: center; border-radius: 8px;">
            <p style="font-size: 14px; color: #a0aec0; margin-top: 0; margin-bottom: 10px; text-transform: uppercase;">Your Unique Administration Key</p>
            <div style="font-family: 'Courier New', Courier, monospace; font-size: 32px; font-weight: bold; color: #48bb78; letter-spacing: 4px; user-select: all;">
                {{ $admin_key }}
            </div>
        </div>

        <p style="font-size: 16px;">
            <strong>Instructions for Login:</strong>
            <ul style="color: #cbd5e0;">
                <li>Keep this key strictly confidential. Do not share it with anyone.</li>
                <li>When you attempt to log in as an Administrator, you will be prompted to enter this key alongside your User ID and Password.</li>
                <li>This key acts as your permanent secondary credential for this account.</li>
            </ul>
        </p>
        
        <div style="background-color: #742a2a; border-left: 4px solid #f56565; padding: 15px; margin-top: 30px; margin-bottom: 30px;">
            <p style="font-size: 14px; color: #fed7d7; margin: 0;">
                <strong>CRITICAL WARNING:</strong> If you lose this key, you will lose access to the Administrator Dashboard. Please store it in a secure password manager immediately.
            </p>
        </div>

        <p style="font-size: 14px; font-weight: 600; color: #cbd5e0; margin-top: 40px;">
            Securely yours,<br>
            Morpho.ID Infrastructure Team<br>
            <span style="font-weight: 400; font-size: 12px; color: #718096;">System Automated Message - Do Not Reply</span>
        </p>
    </div>
@endsection
