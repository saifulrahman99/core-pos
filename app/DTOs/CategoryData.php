<?php

namespace App\DTOs;

final readonly class CategoryData
{
    public function __construct(
        public ?string $name = null,
        public ?string $slug = null,
        public ?string $description = null,
        public ?bool $status = null,
        public ?int $sort_order = null,
    ) {}

    /**
     * Create CategoryData from validated request data.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            slug: $data['slug'] ?? null,
            description: $data['description'] ?? null,
            status: $data['status'] ?? null,
            sort_order: $data['sort_order'] ?? null,
        );
    }

    /**
     * Convert DTO to array, excluding null values (only submitted fields).
     *
     * @return array<string, mixed>
     */
    public function toFilteredArray(): array
    {
        return array_filter(
            (array) $this,
            static fn (mixed $value): bool => $value !== null,
        );
    }
}