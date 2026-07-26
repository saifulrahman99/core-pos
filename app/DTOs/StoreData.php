<?php

namespace App\DTOs;

final readonly class StoreData
{
    public function __construct(
        public ?string $name = null,
        public ?string $tagline = null,
        public ?string $description = null,
        public ?string $phone = null,
        public ?string $whatsapp = null,
        public ?string $email = null,
        public ?string $website = null,
        public ?string $address = null,
        public ?string $google_maps_url = null,
        public ?string $currency = null,
        public ?string $timezone = null,
        public ?string $language = null,
        public ?string $receipt_header = null,
        public ?string $receipt_footer = null,
        public ?string $opening_time = null,
        public ?string $closing_time = null,
    ) {}

    /**
     * Create StoreData from validated request data.
     *
     * @param  array<string, mixed>  $data
     */
    public static function fromArray(array $data): self
    {
        return new self(
            name: $data['name'] ?? null,
            tagline: $data['tagline'] ?? null,
            description: $data['description'] ?? null,
            phone: $data['phone'] ?? null,
            whatsapp: $data['whatsapp'] ?? null,
            email: $data['email'] ?? null,
            website: $data['website'] ?? null,
            address: $data['address'] ?? null,
            google_maps_url: $data['google_maps_url'] ?? null,
            currency: $data['currency'] ?? null,
            timezone: $data['timezone'] ?? null,
            language: $data['language'] ?? null,
            receipt_header: $data['receipt_header'] ?? null,
            receipt_footer: $data['receipt_footer'] ?? null,
            opening_time: $data['opening_time'] ?? null,
            closing_time: $data['closing_time'] ?? null,
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
