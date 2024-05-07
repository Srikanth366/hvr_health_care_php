<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $loginId, $randomPassword;
    
    public function __construct($randomPassword,$name,$loginId)
    {
        $this->randomPassword = $randomPassword;
        $this->name = $name;
        $this->loginId = $loginId;
    }

    
    public function build()
    {
        return $this->view('welcomeemail')->with(['loginId'=> $this->loginId,'randomPassword' => $this->randomPassword,'username'=>$this->name])
                    ->subject('Welcome Email From HVR');
    }

}
