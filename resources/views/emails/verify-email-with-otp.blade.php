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
        <p style="font-size: 16px;">We have received an access request for your login.</p>
        <p style="font-size: 16px;"><b>Here is your validation code:</b></p>
        <p style="font-size: 16px;"><b>{{ $otp }}</b></p>
        <p style="font-size: 16px;">This code will only be valid for 10 minutes.</p>
        <p style="font-size: 16px;"></p>
        <p style="font-size: 16px;">Best regards,</p>
        <p style="font-size: 16px;">{{ config('app.name') }} Team</p>
        <a href="{{ $token_url }}"
            style="display: inline-block; padding: 10px 20px; background-color: #007bff; color: #fff; text-decoration: none; border-radius: 5px;">Verify
            Your Email Address</a>
    </div>
</body>

</html>
