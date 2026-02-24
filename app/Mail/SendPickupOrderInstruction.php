<?php

namespace App\Mail;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class SendPickupOrderInstruction extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Order $order, //Pickup address does not have shipping address
    ) {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Order: Pickup Instructions',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $setting = Setting::where('attribute', 'store_pickup_instructions')->first();
        if (!$setting) {
            throw new \Exception('Store Pickup Instructions setting not found. Please create it in the settings table.');
        }

        return new Content(
            view: 'emails.order-pickup-instruction',
            with: [
                'order' => $this->order,
                'instructions' => $setting->value,
            ],
        );
    }
    
    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
