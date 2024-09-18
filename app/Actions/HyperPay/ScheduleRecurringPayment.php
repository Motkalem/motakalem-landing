<?php

namespace App\Actions\HyperPay;

 use Lorisleiva\Actions\Concerns\AsAction;

class ScheduleRecurringPayment
{
    use AsAction;

    public function handle($installmentPayment)
    {
        $package = $installmentPayment->package;
        $amount = $package->installment_value;

        // Get the number of months from the package
        $numberOfMonths = $package->number_of_months;

        // Calculate start date and end date
        $startDate = now();
        $endDate = $startDate->copy()->addMonths($numberOfMonths);

        // Schedule the first payment 5 minutes from now
        $firstPaymentDate = $startDate->copy()->addMinutes(2);

        // Format dates to the required format
        $formattedStartDate = $startDate->format('Y-m-d H:i:s');
        $formattedEndDate = $endDate->format('Y-m-d H:i:s');
        $formattedFirstPaymentDate = $firstPaymentDate->format('Y-m-d H:i:s');

        $url = "https://eu-prod.oppwa.com/scheduling/v1/schedules";
        $data = "entityId=" . env('RECURRING_ENTITY_ID') .
            "&amount=" . $amount .
            "&paymentType=DB" .
            "&registrationId=" . $installmentPayment->registration_id .
            "&currency=SAR" .
            "&standingInstruction.type=RECURRING" .
            "&standingInstruction.mode=REPEATED" .
            "&standingInstruction.source=MIT" .
            "&standingInstruction.recurringType=SUBSCRIPTION" .
            "&job.second=" . $firstPaymentDate->second .
            "&job.minute=" . $firstPaymentDate->minute .
            "&job.startDate=" . $formattedStartDate .
            "&job.endDate=" . $formattedEndDate .
            "&job.hour=" . $firstPaymentDate->hour .
            "&job.dayOfMonth=" . $firstPaymentDate->day .
            "&job.month=" . $firstPaymentDate->month .
            "&job.dayOfWeek=?" .
            "&shopperResultUrl=" . url('/') .
            "&job.year=" . $firstPaymentDate->year;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Authorization:Bearer ' . env('AUTH_TOKEN')));
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // this should be set to true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $responseData = curl_exec($ch);
        if (curl_errno($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return $responseData;
    }


}
