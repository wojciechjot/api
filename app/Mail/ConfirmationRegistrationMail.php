<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ConfirmationRegistrationMail extends Mailable
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
        $frontUrl = config('services.confirmationRegistrationFrontUrl');
        $token = $this->user->confirmation_token;
        $fullUrl = $frontUrl . $token;

        return $this->markdown('emails.confirmationRegistrationMail')
            ->subject('Aktywacja konta')
            ->with([
                'user' => $this->user,
                'link' => $fullUrl
            ])
        ;
    }
}
