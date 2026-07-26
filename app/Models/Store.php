<?php

namespace App\Models;

use Database\Factories\StoreFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

/**
 * @property int $id
 * @property string $name
 * @property string|null $tagline
 * @property string|null $description
 * @property string|null $phone
 * @property string|null $whatsapp
 * @property string|null $email
 * @property string|null $website
 * @property string|null $address
 * @property string|null $google_maps_url
 * @property string $currency
 * @property string $timezone
 * @property string $language
 * @property string|null $receipt_header
 * @property string|null $receipt_footer
 * @property string|null $opening_time
 * @property string|null $closing_time
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 */
class Store extends Model implements HasMedia
{
    /** @use HasFactory<StoreFactory> */
    use HasFactory, InteractsWithMedia;

    protected $fillable = [
        'name',
        'tagline',
        'description',
        'phone',
        'whatsapp',
        'email',
        'website',
        'address',
        'google_maps_url',
        'currency',
        'timezone',
        'language',
        'receipt_header',
        'receipt_footer',
        'opening_time',
        'closing_time',
    ];

    protected function casts(): array
    {
        return [];
    }

    /**
     * Get the logo media URL.
     */
    public function getLogoUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('logo');

        return $media?->getUrl();
    }

    /**
     * Get the cover image media URL.
     */
    public function getCoverImageUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('cover');

        return $media?->getUrl();
    }

    /**
     * Get the favicon media URL.
     */
    public function getFaviconUrlAttribute(): ?string
    {
        $media = $this->getFirstMedia('favicon');

        return $media?->getUrl();
    }

    /**
     * Register media collections.
     */
    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('logo')
            ->singleFile();

        $this->addMediaCollection('cover')
            ->singleFile();

        $this->addMediaCollection('favicon')
            ->singleFile();
    }
}
