<?php

namespace App\Services;

use App\Models\SupplierPortal;

class SupplierPortalService
{
    protected array $statusIds;

    public function __construct(StatusService $statusService)
    {
        $this->statusIds = $statusService->getSupplierIssueAndSupplierPortal();
    }

    public function changeSupplierPortalStatusToRead(SupplierPortal $supplierPortal): bool
    {
        if ($supplierPortal->status_id !== $this->statusIds['sent']) {
            return false;
        }

        return $supplierPortal->update(['status_id' => $this->statusIds['read']]);

    }
}
