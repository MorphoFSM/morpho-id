@extends('emails.layout')

@section('title', 'Morpho.ID Notification')

@section('content')
<div style="max-width: 650px; margin: 0 auto; background: #2d3748; padding: 45px; border-radius: 12px; border-top: 5px solid #48bb78; box-shadow: 0 10px 25px rgba(0,0,0,0.5);">
        
        <div style="text-align: left; margin-bottom: 35px; border-bottom: 2px solid #4a5568; padding-bottom: 20px;">
            <h2 style="color: #68d391; font-size: 24px; margin-bottom: 5px; text-transform: uppercase; letter-spacing: 1px;">Congratulations! Your Account Has Been Upgraded</h2>
            <p style="color: #a0aec0; font-size: 14px; font-weight: 600;">Morpho.ID Biological Exploration Repository System</p>
        </div>

        <p style="font-size: 16px;">Dear <strong>{{ $Name }}</strong>,</p>
        
        <p style="font-size: 16px;">
            We are thrilled to inform you that your request to become a <strong>System Administrator</strong> for the Morpho.ID platform has been officially reviewed and <strong style="color: #68d391;">APPROVED</strong> by our core management team.
        </p>

        <p style="font-size: 16px;">
            Your account data has been successfully migrated to the highest security clearance tier within our infrastructure. From this moment onward, you will have access to the <strong>Administrator Dashboard</strong>, empowering you with full control over the repository, user management, and system auditing processes.
        </p>
        
        <h3 style="color: #fbd38d; margin-top: 30px; margin-bottom: 15px; font-size: 18px; border-bottom: 1px solid #4a5568; padding-bottom: 5px;">Your Unique Administration Key</h3>
        
        <p style="font-size: 16px;">
            To ensure the integrity of the system, all Administrator accounts require a secondary layer of authentication. The system has securely generated a unique <strong>Administration Key</strong> specifically bound to your new account.
        </p>

        <div style="background-color: #1a202c; border: 1px solid #4a5568; padding: 25px; margin: 25px 0; text-align: center; border-radius: 8px;">
            <div style="font-family: 'Courier New', Courier, monospace; font-size: 34px; font-weight: bold; color: #48bb78; letter-spacing: 4px; user-select: all;">
                {{ $admin_key }}
            </div>
            <p style="font-size: 13px; color: #718096; margin-top: 15px; margin-bottom: 0;">(Please copy and store this key in a safe place)</p>
        </div>

        <h3 style="color: #fbd38d; margin-top: 30px; margin-bottom: 15px; font-size: 18px; border-bottom: 1px solid #4a5568; padding-bottom: 5px;">Important Instructions</h3>
        <p style="font-size: 16px;">
            <ul style="color: #cbd5e0; padding-left: 20px;">
                <li style="margin-bottom: 10px;"><strong>Keep this key strictly confidential.</strong> Do not share it with any colleagues or users.</li>
                <li style="margin-bottom: 10px;">When you attempt to log in as a System Administrator, you will be prompted to enter this key alongside your User ID and Password.</li>
                <li style="margin-bottom: 10px;">Your old user-level access has been completely transferred over. You can no longer log in as a standard user.</li>
            </ul>
        </p>
        
        <div style="background-color: #742a2a; border-left: 4px solid #f56565; padding: 15px; margin-top: 30px; margin-bottom: 30px;">
            <p style="font-size: 14px; color: #fed7d7; margin: 0;">
                <strong>CRITICAL WARNING:</strong> This Administration Key acts as your permanent secondary credential. If you lose this key, you will permanently lose access to the Administrator Dashboard and your account will need to be reset manually by the Super Admin.
            </p>
        </div>

        <p style="font-size: 16px;">
            We thank you for your continued dedication to the Morpho.ID community. We look forward to your contributions in managing and expanding our biological repository.
        </p>

        <p style="font-size: 14px; font-weight: 600; color: #cbd5e0; margin-top: 40px;">
            Securely yours,<br>
            Morpho.ID Infrastructure Team<br>
            <span style="font-weight: 400; font-size: 12px; color: #718096;">System Automated Message - Do Not Reply</span>
        </p>
    </div>
@endsection
