<?php

namespace App\Jobs;

use App\Models\HyperpayWebHooksNotification;
use App\Models\InstallmentPayment;
use App\Models\Installment;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;

class CheckInstallmentsPaymentsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Handle the job.
     *
     * @return void
     */
    public function handle(): void
    {
        Log::notice('Running == CheckInstallmentsPaymentsJob');

        // Get all unpaid installments for active installment payments
        $installments = Installment::where('is_paid', false)
            ->whereHas('installmentPayment', function ($query) {
                $query->where('canceled', false)
                    ->where('is_completed', false);
            })->get();

        $currentDate = now();

        foreach ($installments as $installment) {


            if (env('APP_ENV') == 'production') {

                # Check if the installment date is within the current month
                if (Carbon::parse($installment->installment_date)->isSameMonth($currentDate)) {

                    $this->deductInstallment($installment);
                }
            }

        }
    }

    /**
     * Attempt to deduct payment for the installment.
     *
     * @param Installment $installment
     * @return void
     */
    private function deductInstallment(Installment $installment): void
    {
        $installmentPayment = $installment->installmentPayment;

        $registrationID = $installmentPayment->registration_id;

//        Log::notice('registrationID: ' . $registrationID);

        if($registrationID == null || $registrationID == '' || !$registrationID) {

            return;
        }

        if ($installment->is_paid  ) {
            return;
        }


        $amount = $installment->installment_amount;

        // Prepare Hyperpay API request
        $url = env('SNB_HYPERPAY_URL') . "/registrations/" . $registrationID . "/payments";
        $data = http_build_query([
            'entityId' => env('SNB_RECURRING_ENTITY_ID'),
            'amount' => $amount,
            'currency' => 'SAR',
            'paymentType' => 'DB',
            'standingInstruction.mode' => 'REPEATED',
            'standingInstruction.type' => 'UNSCHEDULED',
            'standingInstruction.source' => 'MIT',
            'shopperResultUrl' => env(env('VERSION_STATE') . 'FRONT_URL'),
        ]);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization:Bearer ' . env('SNB_AUTH_TOKEN'),
        ]);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // This should be true in production
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $responseData = curl_exec($ch);

        if (curl_errno($ch)) {
            $error = curl_error($ch);
            curl_close($ch);

            Log::error('Payment processing error: ' . $error);
            return;
        }
        curl_close($ch);

        $response = json_decode($responseData);

        $this->storeNotification($response, $installmentPayment, $installment);

        if (isset($response->result) && $this->isSuccessfulResponse($response->result->code)) {
            $installment->update([
                'is_paid' => true,
                'paid_at' => now(),
                'admin_ip' => request()->ip(),
            ]);

            // Check if all installments are paid
            if ($installmentPayment->installments()->where('is_paid', false)->doesntExist()) {
                $installmentPayment->update(['is_completed' => true]);
            }
        } else {
            $errorMessage = $response->result->description ?? 'Unknown error occurred.';
            Log::error('Payment failed: ' . $errorMessage);
        }
    }

    /**
     * Check if the response indicates success.
     *
     * @param string|null $resultCode
     * @return bool
     */
    private function isSuccessfulResponse(?string $resultCode): bool
    {
        $successPattern = '/^(000\.000\.|000\.100\.1|000\.[36]|000\.400\.[12]0)/';
        return preg_match($successPattern, $resultCode) === 1;
    }

    /**
     * Store the webhook notification.
     *
     * @param $response
     * @param InstallmentPayment $installmentPayment
     * @param Installment $installment
     * @return void
     */
    private function storeNotification($response, InstallmentPayment $installmentPayment, Installment $installment): void
    {
        HyperpayWebHooksNotification::create([
            'title' => data_get($response, 'result.description'),
            'installment_payment_id' => $installmentPayment->id,
            'type' => 'execute recurring payment',
            'payload' => $response,
            'log' => $response,
        ]);
    }
}
