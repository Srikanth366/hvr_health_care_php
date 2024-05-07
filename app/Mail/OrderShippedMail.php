<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;

class OrderShippedMail extends Mailable
{
    use Queueable, SerializesModels;
    public $randomPassword;
    public $name;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($randomPassword,$name)
    {
        $this->randomPassword = $randomPassword;
        $this->name = $name;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('recoverypassword')->with(['randomPassword' => $this->randomPassword,'username'=>$this->name])
                    ->subject('Password Recovery');
    }

    public function sendWelcomeEmail()
    {
    }
}
