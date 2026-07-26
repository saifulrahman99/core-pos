<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Carbon;

/**
 * @property-read int $id
 * @property-read string|null $log_name
 * @property-read string $description
 * @property-read string|null $event
 * @property-read string $subject_type
 * @property-read int|string $subject_id
 * @property-read string|null $causer_type
 * @property-read int|string|null $causer_id
 * @property-read array<string, mixed>|null $properties
 * @property-read Carbon $created_at
 * @property-read mixed $causer
 */
class ActivityLogResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'log_name' => $this->log_name,
            'description' => $this->description,
            'event' => $this->event,
            'subject_type' => $this->subject_type,
            'subject_id' => $this->subject_id,
            'causer_type' => $this->causer_type,
            'causer_id' => $this->causer_id,
            'properties' => $this->properties,
            'attribute_changes' => $this->attribute_changes,
            'created_at' => $this->created_at->toISOString(),
            'causer' => $this->whenLoaded('causer', function () {
                return [
                    'id' => $this->causer->id,
                    'name' => $this->causer->name,
                    'email' => $this->causer->email,
                ];
            }),
        ];
    }
}
