<?php

namespace App\Http\Controllers\Dashboard\Center;

use App\Http\Controllers\Dashboard\AdminBaseController;
use App\Models\Center\CenterInstallmentPayment;
use App\Notifications\Admin\CenterPaymentUrlNotification;
use App\Traits\HelperTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class CenterOneTimeController extends AdminBaseController
{
    use HelperTrait;

    public function index()
    {
        $title = 'مدفوعات لمرة واحدة';
        $search = request()->query('search');

        $query = \App\Models\CenterPayment::query()->with(['centerPackage', 'centerPatient']);

        if ($search) {
            $query->whereHas('centerPatient', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('mobile_number', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $centerPayments = $query->orderBy('id', 'desc')->paginate(10);

        return view('admin.center-onetime-payments.index', compact('centerPayments', 'title'));
    }

    public function show($id)
    {
        $title = 'عرض الدفعة لمرة واحدة';

        $centerPayment = \App\Models\CenterPayment::with(['centerPackage', 'centerPatient', 'transactions'])
            ->findOrFail($id);

        return view('admin.center-onetime-payments.show', compact('centerPayment', 'title'));
    }

    /**
     * Send payment URL for unfinished payments
     */
    public function sendPaymentUrl(Request $request, $id): JsonResponse
    {

            $centerPayment = \App\Models\CenterPayment::with('centerPatient')->findOrFail($id);

            // Check if payment is already finished
            if ($centerPayment->is_finished) {
                return response()->json([
                    'success' => false,
                    'message' => 'هذا الدفع مكتمل بالفعل'
                ], 400);
            }

            // Generate and send payment URL
            $this->generateOneTimePayUrl($centerPayment);

            return response()->json([
                'success' => true,
                'message' => 'تم إرسال رابط الدفع بنجاح إلى ' . $centerPayment->centerPatient->email
            ]);
    }

    /**
     * Generate one-time payment URL and send notification
     */
    private function generateOneTimePayUrl($centerPayment)
    {
        $patient = $centerPayment->centerPatient;

        $payid = $this->encrypt($centerPayment->id);
        $patid = $this->encrypt($centerPayment->center_patient_id);

        $url = route('checkout.center.onetime.index', [
            'payid' => $payid,
            'patid' => $patid
        ]);

        Notification::route('mail', $patient->email)->notify(new CenterPaymentUrlNotification($patient, $url));

    }
}
