<?php

namespace App\Observers;

use App\Models\RiskControl;
use App\Services\RiskService;

class RiskControlObserver
{
    public function __construct(protected RiskService $riskService) {}

    /**
     * Handle the RiskControl "created" event.
     */
    public function created(RiskControl $riskControl): void
    {
        $this->riskService->updateResidualRisk($riskControl->risk);
    }

    /**
     * Handle the RiskControl "updated" event.
     */
    public function updated(RiskControl $riskControl): void
    {
        $this->riskService->updateResidualRisk($riskControl->risk);
    }

    /**
     * Handle the RiskControl "deleted" event.
     */
    /* public function deleted(RiskControl $riskControl): void
    {
        $this->riskService->updateResidualRisk($riskControl->risk);
    } */
}
