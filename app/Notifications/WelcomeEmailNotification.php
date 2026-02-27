<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WelcomeEmailNotification extends Notification
{
    public function __construct(
        private string $password
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array<int, string>
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to DobaPlay - Your Account Details')
            ->greeting('Hello '.$notifiable->name.'!')
            ->line('Welcome to DobaPlay! Your account has been created by our administrator.')
            ->line('Here are your login details:')
            ->line('**Email:** '.$notifiable->email)
            ->line('**Password:** '.$this->password)
            ->line('For security reasons, we strongly recommend changing your password after your first login.')
            ->action('Login to Your Account', route('login'))
            ->line('If you have any questions or need assistance, please don\'t hesitate to contact our support team.')
            ->salutation('Welcome aboard! The DobaPlay Team');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array<string, mixed>
     */
    public function toArray($notifiable): array
    {
        return [
            'password' => $this->password,
        ];
    }
}
