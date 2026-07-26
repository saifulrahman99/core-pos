<?php

namespace App\DTOs;

final readonly class RoleData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $guard_name = null,
        public ?array $permissions = null,
        public ?int $users_count = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            guard_name: $data['guard_name'] ?? null,
            permissions: $data['permissions'] ?? null,
            users_count: $data['users_count'] ?? null,
        );
    }

    public function toFilteredArray(): array
    {
        return array_filter(
            (array) $this,
            static fn (mixed $value): bool => $value !== null,
        );
    }
}
