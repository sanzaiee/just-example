<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Order Confirmation</title>
</head>
<body style="font-family: Arial, Helvetica, sans-serif; background-color:#f6f6f6; margin:0; padding:0;">
  <table width="100%" cellpadding="0" cellspacing="0">
    <tr>
      <td align="center" style="padding:20px;">
        <table width="600" cellpadding="0" cellspacing="0" style="background-color:#ffffff; padding:20px; border-radius:4px;">
          
          <!-- Header -->
          <tr>
            <td style="text-align:center; padding-bottom:20px;">
              <h2 style="margin:0;">Order Confirmation</h2>
            </td>
          </tr>

          <!-- Greeting -->
          <tr>
            <td style="padding-bottom:15px;">
              <p style="margin:0;">Hi <strong>{{ $order->orderDeliveryAddress->name }}</strong>,</p>
              <p style="margin:8px 0 0 0;">
                Thanks for your order! Here are the details:
              </p>
            </td>
          </tr>

          <!-- Pickup / Delivery -->
          <tr>
            <td style="padding-bottom:15px;">
              <p style="margin:0;">
                <strong>Order No:</strong> {{ $order->pid }}<br>
                @if ($order->is_store_pickup)
                    <strong>Order Type:</strong> Store Pickup<br>
                        <div class="fw-bold"> <code>9AM to 9PM</code><span class="fm-lighter"></span></div>
                @else
                    <strong>Order Type:</strong> Delivery<br>
                    
                    @if ($order->orderDeliveryAddress)
                      @foreach ([
                          ['house_no', 'Apt / Suite / Floor'], 
                          ['address', 'Address'], 
                          // ['name', 'Name'], 
                          // ['email', 'Email'], 
                          ['city', 'City'], 
                          ['postal_code', 'Postal Code']
                          ] as $index => $title)
                          @if ($order->orderDeliveryAddress->{$title[0]})
                              <div class="fw-bold"> <code>{{ $title[1] }} : </code> <span
                                      class="fm-lighter">{{ $order->orderDeliveryAddress->{$title[0]} }}</span></div>
                          @endif
                      @endforeach
                    @endif
                @endif
              </p>
              <strong>Notes:</strong> <br>
              <p style="margin:0;">{{ $order->notes }}</p>
            </td>
          </tr>

          <!-- Order Table -->
          <tr>
            <td>
              <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse:collapse;">
                <tr style="background-color:#eeeeee;">
                  <th align="left" style="border:1px solid #dddddd;"></th>
                  <th align="left" style="border:1px solid #dddddd;">Item</th>
                  <th align="right" style="border:1px solid #dddddd;">Price</th>
                  <th align="right" style="border:1px solid #dddddd;">Quantity</th>
                  <th align="right" style="border:1px solid #dddddd;">Subtotal</th>
                </tr>

                @foreach ($order->orderProductLists as $item)
                <tr>
                  <td style="border:1px solid #dddddd;">
                    <img src="{{ $item->product->image }}" class="" alt="{{ $item->product->name }}" width="50px" height="50px">
                  </td>
                  <td style="border:1px solid #dddddd;">
                    {{ $item->product->name }}
                  </td>
                  <td align="right" style="border:1px solid #dddddd;">$ {{ $item->price }}</td>
                  <td align="right" style="border:1px solid #dddddd;">{{ $item->quantity }}</td>
                  <td align="right" style="border:1px solid #dddddd;">$ {{ $item->price * $item->quantity }}</td>
                </tr>
                @endforeach
                
                <tr>
                  <td colspan="4" align="right" style="border:1px solid #dddddd;"><strong>Shipping</strong></td>
                  <td align="right" style="border:1px solid #dddddd;"><strong>$ {{ $order->shipping_cost }}</strong></td>
                </tr>
                
                <!-- Totals -->
                <tr>
                  <td colspan="4" align="right" style="border:1px solid #dddddd;"><strong>Total</strong></td>
                  <td align="right" style="border:1px solid #dddddd;"><strong>$ {{ $order->amount }}</strong></td>
                </tr>
              </table>
            </td>
          </tr>

          <!-- Footer -->
          <tr>
            <td style="padding-top:20px;">
              <p style="margin:8px 0 0 0;">
                Thanks for choosing us!
              </p>
              <p style="font-size: 16px;"></p>
              <p style="font-size: 16px;">Best regards,</p>
              <p style="font-size: 16px;">{{ config('app.name') }} Team</p>
            </td>
          </tr>

        </table>
      </td>
    </tr>
  </table>
</body>
</html>
