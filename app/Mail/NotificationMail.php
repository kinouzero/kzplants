<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $title;

    public string $messageText;

    public function __construct(string $title, string $messageText)
    {
        $this->title = $title;
        $this->messageText = $messageText;
    }

    public function build()
    {
        return $this->subject($this->title)
            ->view('emails.notification');
    }
}
