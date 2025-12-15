<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Your Email Address</title>
</head>

<body>
    <div style="max-width: 600px; margin: 0 auto; padding: 20px;">
        <h1 style="text-align: center; font-size: 24px; font-weight: bold; color: #000;">Verify Your Email Address</h1>
        <p style="font-size: 16px;">Hello {{ $user->name }},</p>
        <p style="font-size: 16px;">Your OTP is <b>{{ $otp }}</b></p>
        <p style="font-size: 16px;">Please use this OTP to verify your email address.</p>
        <p style="font-size: 16px;">Thank you for using our application.</p>
        <p style="font-size: 16px;">Best regards,</p>
        <p style="font-size: 16px;">{{ config('app.name') }} Team</p>
        <a href="{{ $token_url }}"
            style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Verify
            Your Email Address</a>
    </div>
</body>

</html>
