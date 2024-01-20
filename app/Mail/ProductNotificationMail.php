<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class ProductNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $bodyMessage;

    /**
     * Create a new message instance.
     *
     * @param string $subject E-posta konusu
     * @param string $bodyMessage E-posta mesaj gövdesi
     */
    public function __construct($subject, $bodyMessage)
    {
        $this->subject = $subject;
        $this->bodyMessage = $bodyMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $email = $this->subject($this->subject);

        $htmlBody = "
            <!DOCTYPE html>
            <html lang='en'>
            <head>
                <meta charset='UTF-8'>
                <title>{$this->subject}</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .container { padding: 20px; }
                    .message { margin-top: 20px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='message'>
                        {$this->bodyMessage}
                    </div>
                </div>
            </body>
            </html>
        ";

        return $email->html($htmlBody);
    }
}
