<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Join\JoinRequest;
use App\Models\ParentContract;
use App\Models\User;
use App\Services\JoinService;
use Illuminate\Http\Request;
use App\Notifications\SendContractNotification;
use \Illuminate\Support\Facades\Notification;
use \Illuminate\Support\Facades\Log;
class JoinController extends Controller
{
    public function __construct(private JoinService $joinService)
    {}


    public function store(JoinRequest $request)
    {

        $this->joinService->store($request->validated());


        // $noReplayEmail = "info@motkalem.com";
        // Mail::to($noReplayEmail)->send(new ContactMail($request->all()));

        return response()->json([
            "message" => "Sent Successfully"
        ]);
    }

    public function sendContract($clientOrderPay,$data)
    {

        $validated =  [
            'email' => $clientOrderPay->email,
            'name' => $clientOrderPay->name,
            'age' => $clientOrderPay->age,
            'phone' => $clientOrderPay->phone,
            'city' => $clientOrderPay->city,
            'id_number' => data_get($data,'id_number'),
            'id_end' => data_get($data,'id_end'),
        ];

        return ParentContract::query()->create(array_merge($validated,['accept_terms'=>true]));
    }



    public function notifyClient( $row): void
    {
        try {
            Notification::route('mail', $row->email)
            ->notify(new  SendContractNotification($row));
        }
        catch (\Exception $e) {

            Log::error($e->getMessage());
        }


    }
}
