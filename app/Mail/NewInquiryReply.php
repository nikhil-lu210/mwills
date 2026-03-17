<?php

namespace App\Mail;

use App\Models\ConsultationMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Queue\SerializesModels;

class NewInquiryReply extends Mailable
{
    use Queueable;
    use SerializesModels;

    public function __construct(
        public ConsultationMessage $inquiry,
        public string $replyBody
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            from: new Address(
                config('mail.from.address'),
                config('mail.from.name')
            ),
            subject: __('Thank you for your enquiry') . ' – McWills Consulting',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.new-inquiry-reply',
        );
    }
}
