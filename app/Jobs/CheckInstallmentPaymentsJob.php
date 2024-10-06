<?php

namespace App\Jobs;
namespace App\Jobs;

use App\Actions\HyperPay\ExecuteRecurringPayment;
use App\Models\InstallmentPayment;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class CheckInstallmentPaymentsJob implements ShouldQueue
{
    use Queueable;

    /**
     * @return void
     */
    public function handle(): void
    {

        $installmentPayments = InstallmentPayment::with('package')
            ->where('canceled', false)
            ->get();

        foreach ($installmentPayments as $installment) {

            $firstInstallmentDate = Carbon::parse($installment->first_installment_date);

            $currentDate = Carbon::now();

            $monthsPassed = $firstInstallmentDate->diffInMonths($currentDate);

            if ($installment->package && $monthsPassed <= $installment->package->number_of_months) {

                if ($currentDate->isSameDay($firstInstallmentDate->addMonths($monthsPassed)))
                {

                    $response = ExecuteRecurringPayment::make()->handle($installment->registration_id);

                    Log::info('Installment date 2: '.now()->format('Y-m-d'), ['response' => $response]);
                }
            }

        }
    }
}
