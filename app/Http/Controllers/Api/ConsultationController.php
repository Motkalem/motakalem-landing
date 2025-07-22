<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ConsultantPatient;
use App\Models\ConsultantType;
use App\Traits\HelperTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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


        $consultantType =  ConsultantType::find($request->consultation_type_id);


        $this->notifyAdmin($patient, $consultantType);

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


    protected function notifyAdmin($consultationPatient, $consultationType): void
    {
        try {
            $adminEmails = explode(',', env('ADMIN_EMAILS'));
            foreach ($adminEmails as $adminEmail) {

                 $subject = 'تنبيه بخصوص تسجيل خدمة';

                $logoUrl = 'https://motkalem.sa/assets/img/new-logo-colored.png'; // Update with your logo URL

                $htmlBody = "
            <div style='text-align: right; direction: rtl;font-family: Arial, sans-serif; background-color: #f4f4f4; padding: 20px; margin: 0;'>
                <!-- Email Header -->
                <div style=' text-align: center; padding: 10px 0;'>
                    <img src='{$logoUrl}' alt='Motkalem Logo' style='text-align: right;height: 60px;'>
                </div>

                <!-- Email Content -->
                <div style='text-align: right;background-color: #ffffff; padding: 30px; border-radius: 8px; margin: 20px auto; max-width: 600px; box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);'>

                    <h2 style='text-align: rightcolor: #2d3748; text-align: center;
                     margin-bottom: 20px;'>تم تسجيل خدمة   :
                      </h2>

                    <table style='text-align: right; width: 100%; font-size: 14px; line-height: 1.6; color: #555; border-collapse: collapse; margin-bottom: 20px;'>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>الباقة   :</td>
                            <td style='text-align: right padding: 8px;'>{$consultationType->name}</td>
                        </tr>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>اسم المريض:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->name}</td>
                        </tr>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>الخدمة:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->consultationType?->name}</td>
                        </tr>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>السعر:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->consultationType?->price} ريال</td>
                        </tr>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>العمر:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->age}</td>
                        </tr>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>المدينة:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->city}</td>
                        </tr>
                        <tr>
                            <td style='text-align: right; padding: 8px; font-weight: bold; color: #2d3748;'>رقم الهاتف:</td>
                            <td style='text-align: right padding: 8px;'>{$consultationPatient->mobile}</td>
                        </tr>
                    </table>
                    <div style=' text-align: center; margin-top: 30px;'>
                        <a href='https://motkalem.sa' style='background-color: #06A996; color: #ffffff;
                         text-decoration: none; padding: 12px 25px; font-size: 16px;
                          border-radius: 5px; display: inline-block;'>   الرئيسية  </a>
                    </div>
                </div>

                <!-- Footer -->
                <div style='text-align: center; font-size: 12px; color: #888; margin-top: 20px;'>
                    <p>© 2024 Motkalem. جميع الحقوق محفوظة.</p>
                </div>
            </div>
            ";

                Mail::html($htmlBody, function ($message) use ($adminEmail, $subject) {
                    $message->to($adminEmail)
                        ->subject($subject);
                });
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }
    }

}
