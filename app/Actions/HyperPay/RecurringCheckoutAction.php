<?php

namespace App\Actions\HyperPay;

use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\View\View;
use Lorisleiva\Actions\ActionRequest;
use Lorisleiva\Actions\Concerns\AsAction;

class RecurringCheckoutAction
{
    use AsAction;

    public function handle(ActionRequest $request): Factory|View|Application
    {
        $checkoutId = $request->checkoutId;

        return view('payments.recurring-pay', compact('checkoutId'));
    }
}
