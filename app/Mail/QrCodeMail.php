<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QrCodeMail extends Mailable
{
    use Queueable, SerializesModels;
    public $qrcodeImage;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($qrcodeImage)
    {
        $this->qrcodeImage = $qrcodeImage;
    }

    public function build()
    {
        // Generate a temporary file path to store the QR code image
        $tempFilePath = tempnam(sys_get_temp_dir(), 'qrcode_');
        file_put_contents($tempFilePath, base64_decode(substr($this->qrcodeImage, strpos($this->qrcodeImage, ',') + 1)));

        // Attach the QR code image to the email
        $this->attach($tempFilePath, [
            'as' => 'qrcode.png',
            'mime' => 'image/png',
        ]);

        return $this->subject('QR Code Email Subject')
                    ->text('emails.qrcode_plain') // Optionally, you can provide a plain text version
                    ->view('emails.qrcode'); // Optionally, you can provide an HTML view    
    }
}
