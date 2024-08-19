<?php

namespace App\Actions\HyperPay;

 use Lorisleiva\Actions\Concerns\AsAction;

class ScheduleRecurringPayment
{
    use AsAction;

    public function handle($orderId ,string $token)
    {
        $url = "https://eu-test.oppwa.com/scheduling/v1/schedules";
        $data = "entityId=8ac7a4c790e4d8720190e56cfc7f014f" .
                    "&amount=23.00" .
                    "&paymentType=DB" .
                    "&registrationId=8ac7a4a0915b424c019164b65e632f12" .
                    "&currency=SAR" .
                    "&testMode=EXTERNAL" .
                    "&standingInstruction.type=RECURRING" .
                    "&standingInstruction.mode=REPEATED" .
                    "&standingInstruction.source=MIT" .
                    "&standingInstruction.recurringType=SUBSCRIPTION" .
                    "&job.second=33" .
                    "&job.minute=43" .
                    "&job.startDate=2024-08-16 00:00:00".
                    "&job.endDate=2024-12-16 00:00:00".
                    "&job.hour=7" .
                    "&job.dayOfMonth=5" .
                    "&job.month=*".
                    "&job.dayOfWeek=?" .
                    "&job.year=*";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                       'Authorization:Bearer OGFjN2E0Yzc5MGU0ZDg3MjAxOTBlNTZiYjRiZDAxNDZ8VGczeUNDY0RENmJlRldaOQ=='));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);// this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if(curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }
}
