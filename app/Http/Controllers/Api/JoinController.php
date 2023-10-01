<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Join\JoinRequest;
use App\Services\JoinService;
use Illuminate\Http\Request;

class JoinController extends Controller
{
    public function __construct(private JoinService $joinService)
    {
    }


    public function store(JoinRequest $request)
    {
        $this->joinService->store($request->validated());


        // $noReplayEmail = "info@motkalem.com";
        // Mail::to($noReplayEmail)->send(new ContactMail($request->all()));
        
        return response()->json([
            "message" => "Sent Successfully"
        ]);
    }
}
