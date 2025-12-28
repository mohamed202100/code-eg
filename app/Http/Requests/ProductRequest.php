<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
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
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'price'       => 'required|numeric|min:10',
            'status'      => 'required|in:0,1',
            'image'       => $this->isMethod('post') ? 'required|image|mimes:jpeg,png,jpg,gif,webp|max:2048' : 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'stock'       => 'required|integer|min:0',
        ];
    }
    public function messages(): array
    {
        return [
            'category_id.required' => 'Please select a category.',
            'category_id.exists'   => 'Selected category does not exist.',
            'title.required'       => 'Product title is required.',
            'title.unique'         => 'Product title must be unique.',
            'price.required'       => 'Price is required.',
            'price.numeric'        => 'Price must be a number.',
            'status.required'      => 'Status is required.',
            'status.in'            => 'Status must be active or inactive.',
            'image.required'       => 'Product image is required.',
            'image.image'          => 'File must be an image.',
            'stock.required'       => 'Stock is required.',
            'stock.integer'        => 'Stock must be a number.',
        ];
    }
}
