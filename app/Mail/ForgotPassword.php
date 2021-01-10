<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ForgotPassword extends Mailable
{
    use Queueable, SerializesModels;

    protected $content;

    /**
     * Create a new message instance.
     * @param Array $content Data for send on email
     * @return void
     */
    public function __construct($content)
    {
        $this->content = $content;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.password.forgot')
                    ->with([
                        'username' => $this->content['username'],
                        'confirmationToken' => $this->content['confirmationToken'],
                    ]);
    }
}
