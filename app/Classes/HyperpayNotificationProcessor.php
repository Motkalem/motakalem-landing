<?php

namespace App\Classes;

class HyperpayNotificationProcessor
{
    // Regular expressions based on Hyperpay documentation for all categories
    private $successRegex = '/^(000\.000\.|000\.100\.1)/';
    private $pendingRegex = '/^(000\.200\.|000\.400\.0[^3]|000\.400\.100)/';
    private $reviewRegex = '/^(000\.400\.)/';
    private $rejectionRegex = '/^(800\.|900\.)/';
    private $communicationErrorRegex = '/^(600\.)/';
    private $paymentMethodErrorRegex = '/^(700\.)/';
    private $riskNotificationRegex = '/^(000\.500\.|000\.600\.)/';  // Hypothetical regex for risk notifications

    protected $jsonResponse;

    public function __construct($jsonResponse)
    {
        $this->jsonResponse = $jsonResponse;
    }

    public function processNotification()
    {

        $response = json_decode($this->jsonResponse, true);

        // Check if 'result' and 'code' exist in the response
        if (isset($response['payload']['result']['code'])) {
            $resultCode = $response['payload']['result']['code'];

            if ($this->isSuccess($resultCode)) {
                return 'Transaction was successful.';
            } elseif ($this->isPending($resultCode)) {
                return 'Transaction is pending.';
            } elseif ($this->isUnderReview($resultCode)) {
                return 'Transaction successful but under review.';
            } elseif ($this->isRejection($resultCode)) {
                return 'Transaction was rejected.';
            } elseif ($this->isCommunicationError($resultCode)) {
                return 'Communication error with acquirer or connector.';
            } elseif ($this->isPaymentMethodError($resultCode)) {
                return 'Payment method-specific error.';
            } elseif ($this->isRiskNotification($resultCode)) {
                return 'Transaction involves a risk notification or risk review.';
            } else {
                return 'Unknown result code.';
            }
        }

        return 'Invalid response: result code not found.';
    }

    // Check if the result code matches the success patterns
    private function isSuccess($code)
    {
        return preg_match($this->successRegex, $code);
    }

    // Check if the result code matches the pending patterns
    private function isPending($code)
    {
        return preg_match($this->pendingRegex, $code);
    }

    // Check if the result code matches the review patterns
    private function isUnderReview($code)
    {
        return preg_match($this->reviewRegex, $code);
    }

    // Check if the result code matches the rejection patterns
    private function isRejection($code)
    {
        return preg_match($this->rejectionRegex, $code);
    }

    // Check if the result code matches the communication error patterns
    private function isCommunicationError($code)
    {
        return preg_match($this->communicationErrorRegex, $code);
    }

    // Check if the result code matches the payment method error patterns
    private function isPaymentMethodError($code)
    {
        return preg_match($this->paymentMethodErrorRegex, $code);
    }

    // Check if the result code matches the risk notification patterns
    private function isRiskNotification($code)
    {
        return preg_match($this->riskNotificationRegex, $code);
    }
}



