<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUs\MedicalInquiryRequest;
use App\Models\MedicalInquiry;

class MedicalInquiresController extends Controller
{


    /**
     * @param MedicalInquiryRequest $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(MedicalInquiryRequest $request)
    {
        MedicalInquiry::query()->create($request->validated());

        return response()->json([
            "message" => "Sent Successfully"
        ]);
    }
}
