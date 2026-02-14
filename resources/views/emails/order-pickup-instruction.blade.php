<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Pickup Instructions</title>
</head>

<body style="font-family: Arial, Helvetica, sans-serif; background-color:#f6f6f6; margin:0; padding:0;">

  <div style="width:100%; padding:20px; box-sizing:border-box; background-color:#f6f6f6;">
    <div style="max-width:600px; margin:0 auto; background-color:#ffffff; padding:20px; border-radius:4px; box-sizing:border-box;">

      <!-- Header -->
      <div style="text-align:center; padding-bottom:20px;">
        <h2 style="margin:0;">Pickup Instructions</h2>
      </div>

      <!-- Greeting -->
      <div style="padding-bottom:15px;">
        <p style="margin:0;">Hi <strong>{{ $order->orderDeliveryAddress->name }}</strong>,</p>
        <p style="margin:8px 0 0 0;">
          Please read the following instructions for picking up your order:
        </p>
      </div>

      <div style="border-top:1px solid #e0e0e0; margin:15px 0;"></div>

      <!-- Instructions -->
      <div style="padding-bottom:15px;">
        <p style="margin:0;">
          {{ $instructions }}
        </p>
      </div>

      <div style="border-top:1px solid #e0e0e0; margin:15px 0;"></div>

      <!-- Lockbox Number -->
      <div style="padding-bottom:15px;">
        Your lockbox number is: <strong>{{ $order->admin_notes }}</strong>
      </div>

      <!-- Thank You -->
      <div style="padding-bottom:15px;">
        Thank you so much for your order!
      </div>

      <!-- Footer -->
      <div style="padding-top:20px;">
        <p style="margin:0; font-size:16px;">Best regards,</p>
        <p style="margin:0; font-size:16px;">{{ config('app.name') }} Team</p>
      </div>

    </div>
  </div>

</body>

</html>
