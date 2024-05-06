<?php

namespace App\Actions\Paymob;

use Lorisleiva\Actions\Concerns\AsAction;

class GetAuthToken
{

    use AsAction;

    public function handle()
    {

        $apiKey = env('PAYMOB_API_KEY',
            'ZXlKaGJHY2lPaUpJVXpVeE1pSXNJblI1Y0NJNklrcFhWQ0o5LmV5SmpiR0Z6Y3lJNklrMWxjbU5vWVc1MElpd2ljSEp2Wm1sc1pWOXdheUk2TnpjNUxDSnVZVzFsSWpvaWFXNXBkR2xoYkNKOS40UTVRb0lpa3BoVFN1T0lBVktfTnJIMll3QXBoRmd4cjJBc0NMNVQ3V2RGNlFPNW9Jc1F1TFVON2dQTmFQWTlyT0R1S04zUzhWWUpuLTdqMzBPdUY5UQ');

        $data = [
            'api_key' => $apiKey,
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, 'https://ksa.paymob.com/v1/api/auth/tokens');
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'Accept-Encoding: gzip, deflate, br',
            'Connection: keep-alive',
        ]);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);

        if (curl_errno($ch)) {
            echo 'Curl error: ' . curl_error($ch);
        }

        curl_close($ch);

        $result = json_decode($response);

        return $result->token;
    }
}
