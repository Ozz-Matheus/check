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
        //
        $this->riskService->recalculateRiskControlQualifications($riskControl->risk);
    }

    /**
     * Handle the RiskControl "updated" event.
     */
    public function updated(RiskControl $riskControl): void
    {
        //
        $this->riskService->recalculateRiskControlQualifications($riskControl->risk);
    }

    /**
     * Handle the RiskControl "deleted" event.
     */
    /* public function deleted(RiskControl $riskControl): void
    {
        //
        $this->riskService->recalculateRiskControlQualifications($riskControl->risk);
    } */

    /**
     * Handle the RiskControl "restored" event.
     */
    /* public function restored(RiskControl $riskControl): void
    {
        //
    } */

    /**
     * Handle the RiskControl "force deleted" event.
     */
    /* public function forceDeleted(RiskControl $riskControl): void
    {
        //
    } */
}
