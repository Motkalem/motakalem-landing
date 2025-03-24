<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ContactUs\ProgramInquiryRequest;
use App\Models\ConsultantPatient;
use App\Models\ConsultantType;
use App\Models\ProgramInquiry;
use App\Traits\HelperTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ConsultationController extends Controller
{
    use HelperTrait;

    /**
     * @param Request $request
     * @return JsonResponse|RedirectResponse
     * @throws ValidationException
     */
    public function store(Request $request): JsonResponse|RedirectResponse
    {
        $data = $request->validate([
            'consultation_type_id' => 'required|exists:consultant_types,id',
            'mobile' => 'required|string|max:15',
            'name' => 'required|string|max:255',
            'age' => 'nullable|integer|min:0',
            'gender' => 'nullable|in:male,female',
            'city' => 'nullable|string|max:255',

        ]);

        $formattedMobile = $this->formatMobile($request->input('mobile'));

        $data = array_merge($data, ['mobile' => $formattedMobile]);

        if ($request->expectsJson())
            $data = array_merge($data, ['source' => 'campaign']);

        $patient = ConsultantPatient::query()->create($data);

        $mappedPatient = collect($patient)->only(['name', 'age', 'mobile']);
        return response()->json([
            'success' => true,
            'message' => __('Registered successfully.'),
            'data' => $mappedPatient,
            'payment_url' => $this->generatePaymentLink($patient),
        ], 201);

    }


    /**
     * @param ConsultantPatient $consultantPatient
     * @return string
     */
    private function generatePaymentLink(ConsultantPatient $consultantPatient): string
    {

        $patientPaymentUrl = route('checkout.consultation.index')
            . '?pid=' . $consultantPatient->id;

        return $patientPaymentUrl;
    }


    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getConsultationData(Request $request): JsonResponse
    {
        $consultation = ConsultantType::query()->select(['name', 'price'])->find($request->consultation_type_id);

        return response()->json([
            'success' => true,
            'message' => __('Success.'),
            'data' => $consultation,
        ], 201);
    }
}
