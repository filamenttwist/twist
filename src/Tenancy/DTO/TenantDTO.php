<?php

namespace Twist\Tenancy\DTO;

/**
 * Minimal Tenant model placeholder.
 * In a real Twist application this would likely extend an Eloquent model.
 */
class TenantDTO
{
    public function __construct(
        public int|string|null $id = null,
        public ?string $database = null,
        public array $attributes = [],
    ) {}
}
