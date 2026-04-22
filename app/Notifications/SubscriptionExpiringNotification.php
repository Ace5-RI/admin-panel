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
    protected $paymentLink;

    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct($client, $daysLeft)
    {
        $this->client = $client;
        $this->daysLeft = $daysLeft;
        $this->paymentLink = $paymentLink ?? route('payment.link',['client_id' => $client->id]);
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
       $paymentUrl = $this->generatePaymentLink();

       $message = $this->BuildMessage($paymentUrl);

       $whatsappMessage = WhatsAppMessage::create()->to($this->client->phone_number)->text($message);

       return $whatsappMessage;
    }

    private function generatePaymentLink()
    {
        $paymentUrl = route('payment.invoice', ['client_id' => $this->client->id, 'invoice_id' => $this->generateInvoiceNumber()]);

        return $paymentUrl;
    }

    /**
     * Get the mail representation of the notification.
     */
   private function BuildMessage($paymentUrl)
   {
    $expiryDate = date('d/m/Y', strtotime());
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
