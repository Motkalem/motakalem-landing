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
        $this->userName  = "motkalem";
        $this->apiKey    = "9d4c35dcbb7763e1b97baf9afe49c14067dd80cfd4e97c107ee91ac7adb77591";
        $this->sender    = "Motkalem";
        
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
