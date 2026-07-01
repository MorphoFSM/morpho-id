@extends('emails.layout')

@section('title', 'Profile Update Notification')

@section('content')
<div style="max-width: 600px; margin: 0 auto; background: #1e293b; padding: 30px; border-radius: 10px; border-top: 4px solid #00f0ff;">
        <h2 style="color: #00f0ff;">Morpho.ID Profile Update</h2>
        <p>Hello <strong>{{ $Name }}</strong>,</p>
        <p>We noticed that your Identity Card Number (User ID) was recently updated on your Morpho.ID profile.</p>
        <p><strong>New User ID:</strong> {{ $userid }}</p>
        <p>If you made this change, no further action is required. If you did not authorize this change, please contact our support team immediately.</p>
        <br>
        <p>Thank you,<br><strong>Morpho.ID System</strong></p>
    </div>
@endsection
