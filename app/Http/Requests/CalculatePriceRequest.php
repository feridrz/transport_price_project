<?php

namespace App\Http\Requests;

use App\Rules\ValidCity;
use Illuminate\Foundation\Http\FormRequest;

class CalculatePriceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'addresses' => 'required|array|min:2',
            'addresses.*.country' => 'required|string',
            'addresses.*.zip' => 'required|string',
            'addresses.*.city' => ['required', 'string', new ValidCity()],
        ];
    }
}
