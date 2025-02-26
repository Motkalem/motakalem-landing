<?php

namespace App\Classes;

class HyperpayNotificationProcessor
{
    private $successRegex = '/^(000\.000\.|000\.100\.1)/';
    private $pendingRegex = '/^(000\.200\.|000\.400\.0[^3]|000\.400\.100)/';
    private $reviewRegex = '/^(000\.400\.)/';
    private $rejectionRegex = '/^(800\.|900\.)/';
    private $communicationErrorRegex = '/^(600\.)/';
    private $paymentMethodErrorRegex = '/^(700\.)/';
    private $riskNotificationRegex = '/^(000\.500\.|000\.600\.)/';

    protected $jsonResponse;
    private $registrationInvalidCode = '100.150.203';

    public function __construct($jsonResponse)
    {
        $this->jsonResponse = $jsonResponse;
    }

    public function processNotification()
    {
        $response = json_decode($this->jsonResponse, true);

        if (isset($response['result']['code'])) {
            $resultCode = $response['result']['code'];
        } elseif (isset($response['payload']['result']['code'])) {
            $resultCode = $response['payload']['result']['code'];
        } else {
            return 'استجابة غير صالحة: لم يتم العثور على رمز النتيجة.';
        }

        // Handle specific result codes first
        if ($resultCode === '000.100.110') {
            return 'تمت معالجة الطلب بنجاح في "وضع الاختبار المتكامل للتاجر".';
        } elseif ($resultCode === '800.100.161') {
            return 'تم رفض المعاملة (عدد المحاولات غير الصحيحة تجاوز الحد المسموح به).';
        } elseif ($resultCode === '200.300.404') {
            return 'خطأ في المعاملة: لم يتم العثور على جلسة الدفع للمعرف المطلوب - ربما تم خلط بين خوادم الاختبار/المباشر أو مر أكثر من 30 دقيقة على الدفع.';
        } elseif ($resultCode === '100.396.101') {
            return 'تم إلغاء المعاملة من قبل المستخدم.';
        } elseif ($resultCode === '000.200.000') {
            return 'المعاملة قيد الانتظار.';
        }

        // Process the result code using general regex patterns
        if ($resultCode === $this->registrationInvalidCode) {
            return 'التسجيل غير صالح، ربما تم رفضه في البداية.';
        } elseif ($this->isSuccess($resultCode)) {
            return 'تمت المعاملة بنجاح.';
        } elseif ($this->isPending($resultCode)) {
            return 'المعاملة قيد الانتظار.';
        } elseif ($this->isUnderReview($resultCode)) {
            return 'تمت المعاملة بنجاح ولكنها تحت المراجعة.';
        } elseif ($this->isRejection($resultCode)) {
            return 'تم رفض المعاملة.';
        } elseif ($this->isCommunicationError($resultCode)) {
            return 'خطأ في الاتصال مع المقتدر أو الموصل.';
        } elseif ($this->isPaymentMethodError($resultCode)) {
            return 'خطأ متعلق بطريقة الدفع.';
        } elseif ($this->isRiskNotification($resultCode)) {
            return 'المعاملة تتضمن إشعار خطر أو تحت المراجعة.';
        } else {
            return 'رمز النتيجة غير معروف.';
        }
    }

    private function isSuccess($code)
    {
        return preg_match($this->successRegex, $code);
    }

    private function isPending($code)
    {
        return preg_match($this->pendingRegex, $code);
    }

    private function isUnderReview($code)
    {
        return preg_match($this->reviewRegex, $code);
    }

    private function isRejection($code)
    {
        return preg_match($this->rejectionRegex, $code);
    }

    private function isCommunicationError($code)
    {
        return preg_match($this->communicationErrorRegex, $code);
    }

    private function isPaymentMethodError($code)
    {
        return preg_match($this->paymentMethodErrorRegex, $code);
    }

    private function isRiskNotification($code)
    {
        return preg_match($this->riskNotificationRegex, $code);
    }
}
