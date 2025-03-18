<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use SerializesModels;

    public $invitation;
    public $url;

    public function __construct(Invitation $invitation, string $url)
    {
        $this->invitation = $invitation;
        $this->url = $url;
    }

    public function build()
    {
        $emailContent = "
            <html>
                <head>
                    <title>You are invited to join a project!</title>
                </head>
                <body>
                    <h1>You are invited to join a project!</h1>
                    <p>We are excited to invite you to join the <strong>Project ID: {$this->invitation->project_id}</strong></p>
                    <p>Please click the link below to accept the invitation:</p>
                    <a href='{$this->url}'>Accept Invitation</a>
                    <h3>{$this->url}</h3>
                </body>
            </html>
        ";

        return $this->subject('You are invited to join a project!')->html($emailContent);
    }
}
