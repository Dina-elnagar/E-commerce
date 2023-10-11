<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class OutOfStock extends Mailable
{
    use Queueable, SerializesModels;

    public $product;
    public function __construct( $product)
    {
        $this->product = $product;
    }

    public function build()
    {
        return $this->subject('Product Out Of Stock')
        ->from('sender@example.com', 'Ecommerce Website')
                    ->view('emails.admin');
    }

    public function envelope()
    {
        return new Envelope(
            subject: 'Out Of Stock',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.admin',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
