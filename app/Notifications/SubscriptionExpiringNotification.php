<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Shakil\Fast2sms\Channels\WhatsAppChannel;
use Shakil\Fast2sms\Messages\WhatsAppMessage;

class SubscriptionExpiringNotification extends Notification
{
    protected $client;
    protected $daysLeft;
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($client, $daysLeft)
    {
        $this->client = $client;
        $this->daysLeft = $daysLeft;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return [WhatsAppChannel::class];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toWhatsApp(object $notifiable)
    {
        $message = "";
        $message .= "";
        $message .= "";
        $message .= "";
        $message .= "";
                return (new MailMessage)
            ->line('The introduction to the notification.')
            ->action('Notification Action', url('/'))
            ->line('Thank you for using our application!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
