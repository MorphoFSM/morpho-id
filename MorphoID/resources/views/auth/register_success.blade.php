@extends('emails.layout')

@section('title', 'Morpho.ID Notification')

@section('content')
<div style="max-width: 600px; margin: 0 auto; background: #ffffff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0,0,0,0.05); border-top: 5px solid #00F0FF;">
        
        <div style="text-align: center; margin-bottom: 30px;">
            <h2 style="color: #2d3748; font-size: 24px; margin-bottom: 10px;">Welcome to Morpho.ID!</h2>
            <p style="color: #718096; font-size: 15px;">Your journey into the world of biological exploration starts here.</p>
        </div>

        <p style="font-size: 16px;">Hi <strong>{{ $Name }}</strong>,</p>
        
        <p style="font-size: 16px;">
            Thank you for registering a <strong>User</strong> account on the Morpho.ID platform. We are absolutely thrilled to have you join our vibrant community of researchers, students, and biology enthusiasts from all around!
        </p>

        <p style="font-size: 16px;">
            Morpho.ID is designed to provide you with a seamless and interactive experience. With your new account, you can explore detailed 3D models of biological specimens, compare species side-by-side, dive deep into rich classification taxonomies, and enhance your understanding of nature like never before.
        </p>

        <p style="font-size: 16px; margin-bottom: 30px;">
            To ensure the security of your account and to unlock your full access to all our interactive features, please take a quick moment to verify your email address by clicking the secure button below:
        </p>

        <div style="text-align: center; margin: 40px 0;">
            <a href="{{ $link }}"  class="btn">
                Yes, Verify My Email Address
            </a>
        </div>
        
        <p style="font-size: 15px; color: #4a5568; margin-bottom: 30px; text-align: center;">
            <em>"Exploration is the engine that drives innovation."</em>
        </p>

        <p style="font-size: 14px; color: #718096; margin-top: 40px; border-top: 1px solid #edf2f7; padding-top: 20px;">
            If the button above does not work, you can copy and paste the following link directly into your browser's address bar:<br>
            <span style="color: #4299e1; word-break: break-all;">{{ $link }}</span>
        </p>

        <p style="font-size: 13px; color: #a0aec0; margin-top: 20px;">
            If you did not create an account on Morpho.ID, please safely ignore this email. No further action is required and your information remains secure.
        </p>
        
        <p style="font-size: 14px; font-weight: 600; color: #2d3748; margin-top: 30px;">
            Happy exploring,<br>
            The Morpho.ID Team
        </p>
    </div>
@endsection
