<?php

declare(strict_types=1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Carbon;

class UserInvite extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct() {}

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'You have been invited to join '.config('general.website_name'),
            from: new Address(config('mail.mailers.default.from.email'), config('mail.mailers.default.from.name') ?? config('general.website_name'))
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $link = frontSignedTemporaryRoute('front.register', 'register.store', Carbon::now()->addMinutes(config('fortify.invite.lifetime')));

        return new Content(
            htmlString: <<<HTML
            Invite link: <a href="$link" target="_blank">$link</a><br>
            (Valid for 24 hours)
            
            HTML,
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
