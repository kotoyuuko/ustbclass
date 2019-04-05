<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmail extends \Illuminate\Auth\Notifications\VerifyEmail implements ShouldQueue
{
    use Queueable;

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('验证 E-Mail')
            ->markdown('emails.verify_email', [
                'name' => $notifiable->name,
                'url' => \Illuminate\Support\Facades\URL::temporarySignedRoute(
                    'verification.verify', now()->addMinutes(60), ['id' => $notifiable->getKey()]
                )
            ]);
    }
}
