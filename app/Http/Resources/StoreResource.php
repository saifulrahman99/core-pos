<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property-read int $id
 * @property-read string $name
 * @property-read string|null $tagline
 * @property-read string|null $description
 * @property-read string|null $phone
 * @property-read string|null $whatsapp
 * @property-read string|null $email
 * @property-read string|null $website
 * @property-read string|null $address
 * @property-read string|null $google_maps_url
 * @property-read string $currency
 * @property-read string $timezone
 * @property-read string $language
 * @property-read string|null $receipt_header
 * @property-read string|null $receipt_footer
 * @property-read string|null $opening_time
 * @property-read string|null $closing_time
 * @property-read string|null $logo_url
 * @property-read string|null $cover_image_url
 * @property-read string|null $favicon_url
 */
class StoreResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'phone' => $this->phone,
            'whatsapp' => $this->whatsapp,
            'email' => $this->email,
            'website' => $this->website,
            'address' => $this->address,
            'google_maps_url' => $this->google_maps_url,
            'currency' => $this->currency,
            'timezone' => $this->timezone,
            'language' => $this->language,
            'receipt_header' => $this->receipt_header,
            'receipt_footer' => $this->receipt_footer,
            'opening_time' => $this->opening_time,
            'closing_time' => $this->closing_time,
            'logo_url' => $this->logo_url,
            'cover_image_url' => $this->cover_image_url,
            'favicon_url' => $this->favicon_url,
        ];
    }
}
