@extends('emails.layout')

@section('title', 'Morpho.ID Notification')

@section('content')
<div style="max-width: 650px; margin: 0 auto; background: #ffffff; padding: 45px; border-radius: 12px; border-top: 5px solid #9D4EDD; box-shadow: 0 10px 25px rgba(0,0,0,0.08);">
        
        <div style="text-align: left; margin-bottom: 35px; border-bottom: 2px solid #e2e8f0; padding-bottom: 20px;">
            <h2 style="color: #2d3748; font-size: 26px; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px;">Administrator Access Provisioning</h2>
            <p style="color: #718096; font-size: 14px; font-weight: 600;">Morpho.ID Biological Exploration Repository System</p>
        </div>

        <p style="font-size: 16px;">Dear <strong>{{ $Name }}</strong>,</p>
        
        <p style="font-size: 16px;">
            This email confirms that an <strong>Administrator</strong> account has been provisioned for you on the Morpho.ID platform. As an Administrator, you have been entrusted with elevated privileges that include managing biological specimens, overseeing system classifications, auditing user activities, and maintaining the integrity of the centralized repository.
        </p>

        <p style="font-size: 16px;">
            With great power comes great responsibility. Please ensure that you adhere strictly to our data management protocols. Any changes made to the specimen records are permanently logged and audited to ensure data accuracy for all researchers and students relying on this platform.
        </p>

        <p style="font-size: 16px; margin-bottom: 35px;">
            Before you can access the Administrator Dashboard, your email address must be verified as an active point of contact for security and auditing purposes. Please complete the verification process by clicking the secure activation link below:
        </p>

        <div style="text-align: center; margin: 45px 0;">
            <a href="{{ $link }}"  class="btn">
                Authenticate & Verify Identity
            </a>
        </div>
        
        <div style="background-color: #fffaf0; border-left: 4px solid #ed8936; padding: 15px; margin-top: 30px; margin-bottom: 30px;">
            <p style="font-size: 14px; color: #9c4221; margin: 0;">
                <strong>Security Notice:</strong> If you did not authorize this provisioning request, or if you believe this email was sent in error, please disregard it. Do not forward this email to anyone else.
            </p>
        </div>

        <p style="font-size: 13px; color: #718096; border-top: 1px solid #e2e8f0; padding-top: 20px;">
            <strong>Manual Verification URL:</strong><br>
            If the authentication button above is unresponsive, securely copy and paste the exact URL below into your browser:<br>
            <span style="color: #4299e1; word-break: break-all;">{{ $link }}</span>
        </p>
        
        <p style="font-size: 14px; font-weight: 600; color: #2d3748; margin-top: 40px;">
            Sincerely,<br>
            Morpho.ID Infrastructure Team<br>
            <span style="font-weight: 400; font-size: 12px; color: #a0aec0;">System Automated Message - Do Not Reply</span>
        </p>
    </div>
@endsection
