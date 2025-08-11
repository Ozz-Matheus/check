<?php

namespace App\Contracts;

interface ActionOriginInterface
{
    public function getLabel(): string;

    public function getProcessId(): ?int;

    public function getSubProcessId(): ?int;

    public function getRedirectUrl(): string;

    public function getBreadcrumbs(): array;
}
