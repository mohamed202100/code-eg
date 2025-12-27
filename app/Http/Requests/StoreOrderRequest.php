<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
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
    public function rules()
    {
        return [
            'name'    => 'required|string|max:255',
            'phone'   => 'required|string|max:20|min:11',
            'address' => 'required|string|max:500|min:10',
            'total'   => 'required|numeric|min:1',
        ];
    }
}
