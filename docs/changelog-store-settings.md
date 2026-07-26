# Store Settings Module Changelog

## Current State (2026-07-26)

Module **Store Settings** sudah selesai. Singleton store (hanya 1), tidak ada CRUD — hanya edit.

## Struktur File

```
resources/js/pages/settings/
├── store.tsx                          # barrel re-export → ./store/index
└── store/
    ├── index.tsx                      # Heading + Tabs + TabsList + TabsTrigger + TabsContent
    ├── types.ts                       # StoreData, StoreProps, CurrencyOption, LanguageOption
    ├── GeneralForm.tsx                # useForm: name, tagline, description
    ├── BrandingForm.tsx               # useForm: logo, cover, favicon (forceFormData: true)
    ├── ContactForm.tsx                # useForm: phone, whatsapp, email, website
    ├── AddressForm.tsx                # useForm: address, google_maps_url
    ├── BusinessForm.tsx               # useForm: currency, timezone, language
    ├── ReceiptForm.tsx                # useForm: receipt_header, receipt_footer
    ├── OperationalForm.tsx            # useForm: opening_time, closing_time
    └── components/
        ├── ImagePreview.tsx           # reusable image preview
        └── SaveButton.tsx             # reusable submit button
```

## Arsitektur Backend

- **Controller**: `app/Http/Controllers/Settings/StoreController.php`
  - `edit()` — render `settings/store` dengan `StoreResource`
  - `update()` — terima `$request->validated()` (partial), pass langsung ke service
- **Service**: `app/Services/StoreService.php`
  - `get()` — `firstOrCreate` (auto-create default jika belum ada)
  - `update(array $data, array $files)` — hanya update key yang ada di array (partial update)
- **DTO**: `app/DTOs/StoreData.php` — semua field nullable (dipakai di tempat lain jika perlu)
- **Request**: `app/Http/Requests/Settings/UpdateStoreRequest.php`
  - Semua rule `sometimes` (bukan `required`) — per-tab save
  - `prepareForValidation()` hanya convert empty string → null untuk field yang **ada** di request
- **Resource**: `app/Http/Resources/StoreResource.php` — wrap data di `{ data: { ... } }`
- **Policy**: `app/Policies/StorePolicy.php` — view/update selalu true
- **Routes**: `routes/settings.php` — `GET/PATCH /settings/store` (auth middleware)

## Keputusan Penting

### Per-Tab Save
- Setiap form punya `useForm()` sendiri, tidak ada shared state
- Submit hanya kirim field tab tersebut
- Backend `sometimes` validation — field dari tab lain tidak divalidasi
- `$request->validated()` hanya berisi field yang di-submit → service hanya update field itu

### Type Inertia
- `StoreResource` (JsonResource) serializes sebagai `{ data: { ... } }`
- Type prop: `StoreProps = { data: StoreData }`
- `index.tsx` terima `store: StoreProps`, kirim `store.data` ke setiap form

### Barrel Re-export
- `settings/store.tsx` → `export { default } from './store/index'`
- Diperlukan karena Inertia view finder resolve `settings/store` ke file `store.tsx`

### Form Submission
- `patch(update.url(), { preserveScroll: true })` — kirim semua field dari useForm
- Branding: `forceFormData: true` untuk file upload
- Error handler: tampilkan first error via `toast.error()`
- Success handler: server-side flash → `useFlashToast` hook

## File yang Tidak Diubah

- Route, Controller (endpoint sama), Request validation rules
- Layout, design, nama field database
- Settings module lain: profile, appearance, security

## Backend Validation Rules (UpdateStoreRequest)

```php
'name'             => ['sometimes', 'required', 'string', 'max:255'],
'tagline'          => ['nullable', 'string', 'max:255'],
'description'      => ['nullable', 'string', 'max:1000'],
'phone'            => ['nullable', 'string', 'max:50'],
'whatsapp'         => ['nullable', 'string', 'max:50'],
'email'            => ['nullable', 'email', 'max:255'],
'website'          => ['nullable', 'url', 'max:255'],
'address'          => ['nullable', 'string', 'max:1000'],
'google_maps_url'  => ['nullable', 'url', 'max:255'],
'currency'         => ['sometimes', 'required', 'string', 'max:10'],
'timezone'         => ['sometimes', 'required', 'string', 'max:50'],
'language'         => ['sometimes', 'required', 'string', 'max:10'],
'receipt_header'   => ['nullable', 'string', 'max:255'],
'receipt_footer'   => ['nullable', 'string', 'max:255'],
'opening_time'     => ['nullable', 'date_format:H:i'],
'closing_time'     => ['nullable', 'date_format:H:i'],
'logo'             => ['nullable', 'file', 'image', 'max:2048'],
'cover'            => ['nullable', 'file', 'image', 'max:4096'],
'favicon'          => ['nullable', 'file', 'image', 'max:1024'],
```

## Tests

- 17 tests di `tests/Feature/StoreSettingsTest.php`
- 56 total tests — semua passing
