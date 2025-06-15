<?php

namespace App\Actions\Api\General;

use App\Models\Package;
use Lorisleiva\Actions\Concerns\AsAction;

class GetPackages
{
    use AsAction;

    public function handle()
    {
        $packages = Package::where('is_active', 1)->get()
        ->map(function ($package) {
            if ($package->payment_type === Package::TABBY) {
                $package->payment_type = Package::ONE_TIME;
            }
            return $package;
        });

        $response = [
            'status' => 1,
            'message' => 'Get packages',
            'payload' => $packages,
        ];

        return response()->json($response, 200);
    }
}
