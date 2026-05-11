<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Http;

class WhatsappHelper
{
    protected $apiKey;
    protected $apiUrl = 'https://api.fonnte.com/send';

    public function __construct()
    {
        $this->apiKey = env('FONNTE_API_KEY');
    }

    public static function sendInteractive($phone, $message, $buttons)
    {
        $apiKey = env('FONNTE_API_KEY');

        $response = Http::witHeaders([
            'Authorization' => $apiKey
        ])->post('https://api.fonnte.com/send', [
            'target' => $phone,
            'message' => $message,
            'buttons' => $buttons
        ]);

        return $response->json();
    }

    public static function sendFile($phone, $fileurl, $caption ='')
    {
        $apiKey = env(' FONNTE_API_KEY');

            $response = Http::withHeaders([
                'Authorization' => $apiKey
            ])->post('https://api.fonnte.com/send', [
                'target' => $phone,
                'url' => $fileurl,
                'caption' => $caption 
            ]);

            return $response->json();
    }
}