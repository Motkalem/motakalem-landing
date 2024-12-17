<?php

namespace App\Http\Support;

use App\Jobs\SendSMSJob;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;

class SMS
{
    // (new SMS())->setPhone($mobile)->SetMessage($msg)->build();

    // ============================= //
    // https://www.dreams.sa/index.php/api/sendsms/?user=Dreams&secret_key=******************&to=966500000000&message=lasttest&sender=sendername&date=2017-07-31&time=10:30:01
    protected $vendorUrl    = "https://www.dreams.sa/index.php/api/sendsms/"; // SMS Vendor Url
    protected $userName     = ""; // SMS userName
    protected $apiKey       = ""; // SMS apiKey
    protected $sender       = ""; // SMS Sender
    // ============================= //
    protected $message      = ""; // Message Text
    protected $mobile        = ""; // Phone Number

    protected $origin        = "";

    public function __construct() {
//
//        $this->userName  = "motkalem";
//        $this->apiKey    = "f826bc3e116699ffc69a2e3a0c208a0a70632aedcdb77dc637daa77e9866b53c";
//        $this->sender    = "Motkalem";

        $this->userName  = "Square";
        $this->apiKey    = "57fc4447c3abff7c17ba80d89a89a84e01df1ea428fcd03e17f85af41dfebfe5";
        $this->sender    = "Laser";
        $this->origin = $this->getRequestOrigin();
    }

    public function setPhone($mobile) {

        if (str_contains($mobile, "+")) {
            $mobile = explode('+',$mobile)[1];
        }
        $this->mobile = $mobile;
        return $this;
    }

    public function setMessage($message) {
        $this->message = $message;
        return $this;
    }

    public function getFields() {
        return  "?user=".$this->userName.
                "&secret_key=".$this->apiKey.
                "&to=".$this->mobile.
                "&message=".$this->message.
                "&sender=".$this->sender.
                "&date=".Carbon::now()->format('y-m-d').
                "&time=".Carbon::now()->format('m:i:s')."";
    }

    public function build($priority = true) {
//        if(env('SEND_SMS') == true /*&& $this->origin === 'customer'*/) {
//            if ($priority) {
                return Http::get($this->vendorUrl . $this->getFields())->json();
//            } else {
//                SendSMSJob::dispatch($this->vendorUrl, $this->getFields());
//            }
//        }
    }

    public function getRequestOrigin() {
        $url = Request::url();
        if (str_contains($url, 'customer')) {
            return 'customer';
        } elseif (str_contains($url, 'technician')) {
            return 'technician';
        }
        return 'unknown';
    }
}
