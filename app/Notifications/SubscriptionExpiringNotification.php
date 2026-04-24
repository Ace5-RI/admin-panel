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
    $expiryDate = date('d/m/Y', strtotime($this->client->subscription_end_date));
    $amount = number_format($this->client->revenue, 0, ',','.');

        $message = "═══════════════════════════════════\n";
        $message .= "     *⚠️ PERINGATAN LANGGANAN* ⚠️\n";
        $message .= "═══════════════════════════════════\n\n";
        
        $message .= "Kepada Yth.\n";
        $message .= "*{$this->client->name}*\n";
        $message .= "{$this->client->company}\n\n";
        
        $message .= "📅 *Masa Langganan Anda akan berakhir dalam*\n";
        $message .= "*⏰ {$this->daysLeft} HARI LAGI!*\n\n";
        
        $message .= "📋 *Detail Langganan:*\n";
        $message .= "┌─────────────────────────────────┐\n";
        $message .= "│ Tanggal Berakhir: {$expiryDate}\n";
        $message .= "│ Tagihan: Rp {$amount}\n";
        $message .= "└─────────────────────────────────┘\n\n";
        
        $message .= "💳 *Lakukan pembayaran melalui link di bawah ini:*\n";
        $message .= "🔗 *LINK PEMBAYARAN:*\n";
        $message .= "{$paymentUrl}\n\n";
        
        $message .= "Atau transfer ke:\n";
        $message .= "🏦 Bank BCA: 123-456-789\n";
        $message .= "📱 Atas nama: Admin Panel\n\n";
        
        $message .= "📌 *Setelah transfer, kirim bukti ke nomor ini.*\n\n";
        
        $message .= "Terima kasih 🙏\n";
        $message .= "*Admin Panel*";

        return $message;
   }

   private function generateInvoiceNumber()
   {
    return 'INV/' . date('Ymd') . '/' . str_pad($this->client->id, 4, '0', STR_PAD_LEFT);
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
