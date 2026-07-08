<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FlightSearchRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'tripType' => ['required', Rule::in(['one_way', 'round_trip', 'multi_city'])],
            'origin' => ['required', 'string', 'size:3', 'alpha'],
            'destination' => ['required', 'string', 'size:3', 'alpha', 'different:origin'],
            'departureDate' => ['required', 'date', 'after_or_equal:today'],
            'returnDate' => ['nullable', 'date', 'after_or_equal:departureDate', 'required_if:tripType,round_trip'],
            'adults' => ['required', 'integer', 'min:1', 'max:9'],
            'children' => ['nullable', 'integer', 'min:0', 'max:9'],
            'infants' => ['nullable', 'integer', 'min:0', 'max:9'],
            'travelClass' => ['required', Rule::in(['ECONOMY', 'PREMIUM_ECONOMY', 'BUSINESS', 'FIRST'])],
            'nonStop' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'origin' => strtoupper(trim((string) $this->input('origin', ''))),
            'destination' => strtoupper(trim((string) $this->input('destination', ''))),
            'travelClass' => strtoupper((string) $this->input('travelClass', 'ECONOMY')),
            'children' => (int) $this->input('children', 0),
            'infants' => (int) $this->input('infants', 0),
        ]);
    }
}
