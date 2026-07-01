@extends('emails.layout')

@section('title', 'Reset Password')

@section('content')
<h2>Morpho.ID - Password Reset Request</h2>
    <p>Dear User,</p>
    <p>You are receiving this email because we received a password reset request for your Morpho.ID account. This request was initiated to help you regain access to your Morpho.ID system.</p>
    <p>Please click the button below to proceed with setting up a new password for your account. Ensure that you choose a strong password to maintain your account's security.</p>
    
    <p>
        <a href="{{ $link }}"  class="btn">
            Reset Password
        </a>
    </p>

    <p>If you did not request a password reset, no further action is required on your part. Your account remains secure.</p>
    <p>For any further assistance, please contact our support team.</p>
@endsection
