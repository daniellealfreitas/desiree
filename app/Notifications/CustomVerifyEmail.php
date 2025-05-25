<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;
use App\Models\User;
use Illuminate\Support\Facades\Notification;

class CustomVerifyEmail extends BaseVerifyEmail implements ShouldQueue
{
    use Queueable;

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }

    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        // Enviar cópias para admins em um job separado (não bloquear o email principal)
        dispatch(function () use ($notifiable, $verificationUrl) {
            $this->sendCopyToAdmins($notifiable, $verificationUrl);
        })->delay(now()->addSeconds(5)); // Delay de 5 segundos

        return (new MailMessage)
            ->subject('Verificação de Email - ' . config('app.name'))
            ->view('emails.verify-email', [
                'user' => $notifiable,
                'verificationUrl' => $verificationUrl,
            ]);
    }

    /**
     * Send copy of verification email to admins and contact email
     */
    protected function sendCopyToAdmins($user, $verificationUrl)
    {
        // Get admin users
        $adminUsers = User::where('role', 'admin')->get();

        // Contact email
        $contactEmail = 'contato@swingcuritiba.com.br';

        // Prepare admin notification data
        $adminData = [
            'user' => $user,
            'verificationUrl' => $verificationUrl,
            'isAdminCopy' => true,
        ];

        // Send to admin users
        foreach ($adminUsers as $admin) {
            try {
                $admin->notify(new AdminEmailVerificationCopy($adminData));
            } catch (\Exception $e) {
                \Log::error('Failed to send admin copy to user ' . $admin->id . ': ' . $e->getMessage());
            }
        }

        // Send to contact email
        try {
            Notification::route('mail', $contactEmail)
                ->notify(new AdminEmailVerificationCopy($adminData));
        } catch (\Exception $e) {
            \Log::error('Failed to send admin copy to contact email: ' . $e->getMessage());
        }
    }
}
