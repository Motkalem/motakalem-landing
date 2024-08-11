<?php
namespace App\Http\Controllers;

class PaymentController extends Controller{


    public function getPayPage(){
        function request() {
            $url = "https://eu-test.oppwa.com/v1/checkouts";
            $data = "entityId=8a8294174d0595bb014d05d829cb01cd" .
                        "&amount=92.00" .
                        "&currency=SAR" .
                        "&paymentType=DB";

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                           'Authorization:Bearer OGE4Mjk0MTc0ZDA1OTViYjAxNGQwNWQ4MjllNzAxZDF8OVRuSlBjMm45aA=='));
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $responseData = curl_exec($ch);
            if(curl_errno($ch)) {
                return curl_error($ch);
            }
            curl_close($ch);

            return  json_decode($responseData, true) ;
        }
            $responseData = request();

        return view('payments.one-time-pay', compact('responseData'));
    }
}
?>
