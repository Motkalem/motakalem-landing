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

    public function sendContract(Request $request)
    {


        $vlaidated = $request->validate([
            'email' => 'email|required',
            'name' => 'required|string',
            'age' => 'required|integer',
            'phone' => 'required|string',
            'city' => 'required|string',
            'id_number' => 'required|digits:10',
            'id_end' => ['required','date',
            function ($attribute, $value, $fail) {
                if (strtotime($value) <= strtotime(now())) {
                    $fail('يجب ان يكون تاريخ الإنتهاء لاحق لتاريخ اليوم');
                }
            },],

        ]);

        $row = ParentContract::create(array_merge($vlaidated,['accept_terms'=>true]));

        $this->notifyClient( $row);
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
