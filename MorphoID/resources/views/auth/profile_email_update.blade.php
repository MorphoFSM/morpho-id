@extends('emails.layout')

@section('title', 'Verify Your New Email Address')

@section('content')
<div style="max-width: 600px; margin: 0 auto; background: #1e293b; padding: 30px; border-radius: 10px; border-top: 4px solid #00f0ff;">
        <h2 style="color: #00f0ff;">Morpho.ID - Verify New Email</h2>
        <p>Hello <strong>{{ $Name }}</strong>,</p>
        <p>Your Morpho.ID profile email address was recently updated. To ensure the security of your account and complete this change, please verify your new email address by clicking the button below.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $link }}"  class="btn">Verify Email</a>
        </div>
        
        <p>If you did not request this change, please ignore this email or contact support.</p>
        <br>
        <p>Thank you,<br><strong>Morpho.ID System</strong></p>
    </div>
@endsection
