<?php

namespace Modules\DocumentSign\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DocumentMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $senderName;
    protected $senderEmail;
    protected $mailData;
    /**
     * Create a new message instance.
     *
     * @return void
     */
     public function __construct($senderName, $senderEmail, $mailData)
     {
         $this->senderName = $senderName;
         $this->senderEmail = $senderEmail;
         $this->mailData = $mailData;
     }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
      return $this->from($this->senderEmail, $this->senderName)
                    ->subject('Complete with Sign: ' . $this->mailData['title'])
                    ->view('documentsign::emails.documentMail')
                    ->with(['mailData' => $this->mailData]);
    }
}
