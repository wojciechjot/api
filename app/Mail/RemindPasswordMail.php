<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class RemindPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    private User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $frontUrl = config('services.remindPasswordFrontUrl');
        $token = $this->user->confirmation_token;
        $fullUrl = $frontUrl . $token;

        return $this->markdown('emails.remindPasswordMail')
            ->subject('Przypomnienie hasła')
            ->with([
                'user' => $this->user,
                'link' => $fullUrl
            ])
        ;
    }
}
