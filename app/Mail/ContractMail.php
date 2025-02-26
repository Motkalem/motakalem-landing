<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;

class ContractMail extends Mailable
{
    public $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function build()
    {


        return $this->view('emails.contract')
            ->with(['data' => $this->data])
            ->subject('تحديث علي عقد الإشتراك - فريق متكلم');
    }
}
