@extends('emails.layout')

@section('title', 'Morpho.ID Notification')

@section('content')
<div style="max-width: 650px; margin: 0 auto; background: #2d3748; padding: 40px; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); border-top: 6px solid #ef4444;">
        
        <div style="text-align: center; margin-bottom: 35px;">
            <div style="font-size: 45px; margin-bottom: 15px;">🚫</div>
            <h2 style="color: #ffffff; font-size: 26px; margin-bottom: 10px; font-weight: 800; letter-spacing: 1px;">Admin Registration Rejected</h2>
            <p style="color: #a0aec0; font-size: 15px;">Update on Your Application</p>
        </div>

        <p style="font-size: 16px; color: #e2e8f0;">Hi <strong>{{ $Name }}</strong>,</p>
        
        <p style="font-size: 16px; color: #e2e8f0;">
            Thank you for your interest in joining the Administration Team on the Morpho.ID platform.
        </p>

        <p style="font-size: 16px; color: #e2e8f0;">
            After careful review, the System Administrator has decided to <strong>reject</strong> your application for Administrator privileges at this time.
        </p>

        <p style="font-size: 16px; margin-bottom: 35px; color: #e2e8f0;">
            Your registration data has been securely removed from our system. If you believe this was a mistake, or if you simply wish to access the repository as a normal user, you are welcome to register a new account and select the <strong>User</strong> role instead.
        </p>

        <div style="text-align: center; margin: 45px 0;">
            <a href="{{ url('/register') }}"  class="btn" style="background-color: #4a5568; border-color: #718096; color: #ffffff;">
                Register New Account
            </a>
        </div>
        
        <p style="font-size: 14px; font-weight: 600; color: #cbd5e0; margin-top: 35px; border-top: 1px solid #4a5568; padding-top: 25px;">
            Thank you for understanding,<br>
            <span style="color: #00F0FF;">The Morpho.ID Security System</span>
        </p>
    </div>
@endsection
