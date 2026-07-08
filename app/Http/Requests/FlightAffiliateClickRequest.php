<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class FlightAffiliateClickRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'flight_id' => ['required', 'string', 'max:255'],
            'affiliate_url' => ['required', 'url', 'max:2000'],
            'affiliate_name' => ['required', 'string', 'max:100'],
            'airline' => ['nullable', 'string', 'max:100'],
            'flight_number' => ['nullable', 'string', 'max:50'],
            'origin' => ['required', 'string', 'size:3', 'alpha'],
            'destination' => ['required', 'string', 'size:3', 'alpha'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'currency' => ['nullable', 'string', 'max:10'],
            'flight_search_id' => ['nullable', 'integer', 'exists:flight_searches,id'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'origin' => strtoupper(trim((string) $this->input('origin', ''))),
            'destination' => strtoupper(trim((string) $this->input('destination', ''))),
            'affiliate_url' => filter_var($this->input('affiliate_url'), FILTER_SANITIZE_URL),
        ]);
    }
}
