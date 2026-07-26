<?php

namespace App\DTOs;

final readonly class UserData
{
    public function __construct(
        public ?int $id = null,
        public ?string $name = null,
        public ?string $email = null,
        public ?bool $is_active = null,
        public ?string $email_verified_at = null,
        public ?string $created_at = null,
        public ?string $updated_at = null,
        public ?array $roles = null,
        public ?string $avatar_url = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? null,
            email: $data['email'] ?? null,
            is_active: $data['is_active'] ?? null,
            email_verified_at: $data['email_verified_at'] ?? null,
            created_at: $data['created_at'] ?? null,
            updated_at: $data['updated_at'] ?? null,
            roles: $data['roles'] ?? null,
            avatar_url: $data['avatar_url'] ?? null,
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
