<?php

namespace App\Listeners;

use App\Events\InsuranceVerified;
use App\Services\InsuranceVerificationService;

class ProcessInsuranceVerification
{
    protected $insuranceService;

    public function __construct(InsuranceVerificationService $insuranceService)
    {
        $this->insuranceService = $insuranceService;
    }

    public function handle(InsuranceVerified $event)
    {
        $order = $event->order;
        
        if ($order->is_insurance_verified) {
            $this->insuranceService->processInsuranceStatus($order);
        }
    }
}
