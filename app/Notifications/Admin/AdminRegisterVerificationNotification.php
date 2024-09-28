<?php

namespace App\Notifications\Admin;

use App\Traits\NotificationHasTranslationsTrait;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AdminRegisterVerificationNotification extends Notification
{
    use Queueable;

    private $translations = [
        'en' => [
            'line_1' => 'Your admin account registration almost done. Please click verify button to verify your email.',
            'button' => 'Verify'
        ],
        'id' => [
            'line_1' => 'Pendaftaran akun admin Anda hampir selesai. Silakan klik tombol verifikasi untuk memverifikasi email Anda.',
            'button' => 'Verifikasi'
        ]
    ];

    private $firstName;
    private $validationUrl;
    
    public function __construct($firstName, $validationUrl)
    {
        $this->firstName        = $firstName;
        $this->validationUrl    = $validationUrl;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $translations = $this->translations[app()->getLocale()];
        return (new MailMessage)
                    ->subject(env('APP_EMAIL_COMPANY') . ' - Admin Regitration')
                    ->greeting('Hi ' . $this->firstName . ',')
                    ->line($translations['line_1'])
                    ->action($translations['button'], $this->validationUrl);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
