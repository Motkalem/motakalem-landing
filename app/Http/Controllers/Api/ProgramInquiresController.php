<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUs\ProgramInquiryRequest;
use App\Models\ProgramInquiry;

class ProgramInquiresController extends Controller
{


    /**
     * @param ProgramInquiryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(ProgramInquiryRequest $request)
    {
        ProgramInquiry::query()->create($request->validated());

        return response()->json([
            "message" => "Sent Successfully"
        ]);
    }
}
