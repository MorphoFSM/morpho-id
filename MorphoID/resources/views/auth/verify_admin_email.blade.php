@extends('emails.layout')

@section('title', 'Morpho.ID Notification')

@section('content')
<div style="max-width: 650px; margin: 0 auto; background: #2d3748; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); border-top: 6px solid #9D4EDD;">
        
        <div style="text-align: center; margin-bottom: 35px;">
            <div style="font-size: 45px; margin-bottom: 15px;">🛡️</div>
            <h2 style="color: #ffffff; font-size: 26px; margin-bottom: 10px; font-weight: 800; letter-spacing: 1px;">Admin Role Approved!</h2>
            <p style="color: #a0aec0; font-size: 15px;">Action Required: Verify Your Upgrade</p>
        </div>

        <p style="font-size: 16px; color: #e2e8f0;">Hi <strong>{{ $Name }}</strong>,</p>
        
        <p style="font-size: 16px; color: #e2e8f0;">
            Congratulations! The System Administrator has approved your request to upgrade your account to <strong>System Administrator</strong> on the Morpho.ID platform.
        </p>

        <p style="font-size: 16px; color: #e2e8f0;">
            Before we can grant you full access to the Administration Dashboard and generate your secure Administration Key, you are required to verify this account upgrade.
        </p>

        <p style="font-size: 16px; margin-bottom: 35px; color: #e2e8f0;">
            Please click the secure button below to confirm your upgrade and generate your unique Administration Key:
        </p>

        <div style="text-align: center; margin: 45px 0;">
            <a href="{{ $link }}"  class="btn">
                Verify & Generate Key
            </a>
        </div>
        
        <div style="background-color: rgba(239, 68, 68, 0.1); border-left: 4px solid #ef4444; padding: 15px 20px; margin-bottom: 35px; border-radius: 4px;">
            <p style="margin: 0; font-size: 14px; color: #fca5a5;">
                <strong>⚠️ Note:</strong> You will not be able to log in to the system until you complete this verification step.
            </p>
        </div>

        <p style="font-size: 14px; color: #a0aec0; margin-top: 40px; border-top: 1px solid #4a5568; padding-top: 25px;">
            If the button above does not work, you can copy and paste the following link directly into your browser's address bar:<br>
            <span style="color: #63b3ed; word-break: break-all;">{{ $link }}</span>
        </p>
        
        <p style="font-size: 14px; font-weight: 600; color: #cbd5e0; margin-top: 35px;">
            Welcome to the Administration Team,<br>
            <span style="color: #00F0FF;">The Morpho.ID Security System</span>
        </p>
    </div>
@endsection
