<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Join\JoinRequest;
use App\Models\Package;
use App\Models\ParentContract;
use App\Models\User;
use App\Services\JoinService;
use App\Traits\HelperTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Notifications\SendContractNotification;
use \Illuminate\Support\Facades\Notification;
use \Illuminate\Support\Facades\Log;
class JoinController extends Controller
{
    use HelperTrait;
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

    /**
     * @param $student
     * @param $package_id
     * @return mixed
     */
    public function sendContract($student, $package_id)
    {

        $validated =  [
            'email' => $student->email,
            'name' =>  $student->name,
            'age' =>  $student->age,
            'phone' =>  $student->phone,
            'city' => $student->city,
            'id_number' =>  $student->id_number,
            'id_end' => $student->id_end,
            //'accept_terms' => 'required|boolean',
        ];


        $package = Package::query()->find($package_id);


        $data = array_merge($validated,
            [
                'package_id' =>  $package_id,
                'package_starts_date' =>  $package?->starts_date,
                'package_ends_date' =>  $package?->ends_date,
            ]
        );
         $contract = ParentContract::query()->firstOrCreate(
            [
                'phone' =>  $student->phone,
            ],
             $data );

        $contract = $contract->load('package');

        $this->notifyClient($contract);

        return $contract;
    }

    /**
     * @param $row
     * @return void
     */
    public function notifyClient( $row): void
    {
        try {

            $row = $row->load('package');

            Notification::route('mail', $row->email)->notify(new SendContractNotification($row));
        }
        catch (\Exception $e) {

            Log::error($e->getMessage());
        }
    }
}
