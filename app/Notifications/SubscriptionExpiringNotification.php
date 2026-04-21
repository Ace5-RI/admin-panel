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
    public function toWhatsApp(object $notifiable)
{
    $paymentUrl = route('payment.page', ['id' => $this->client->id]);
    $expiryDate = date('d/m/Y', strtotime($this->client->subscription_end_date));
    
    $message = "⚠️ *PEMBERITAHUAN LANGGANAN* ⚠️\n\n";
    $message .= "Halo *{$this->client->name}*,\n\n";
    $message .= "Langganan Anda akan berakhir dalam *{$this->daysLeft} hari*.\n";
    $message .= "📅 {$expiryDate}\n\n";
    $message .= "💳 *Link Pembayaran:*\n";
    $message .= "{$paymentUrl}\n\n";
    $message .= "Atau transfer ke:\n";
    $message .= "BCA: 123-456-789 a.n Admin Panel\n\n";
    $message .= "Kirim bukti transfer ke WA ini ya 🙏\n\n";
    $message .= "Terima kasih!";
    
    return WhatsAppMessage::create()
        ->to($this->client->phone_number)
        ->text($message);
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
