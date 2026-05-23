<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    public function rules(): array
    {
        return [
            'name'              => 'required|string|max:255',
            'slug'              => 'nullable|string|max:255|unique:products,slug',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'old_price'         => 'nullable|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'badge'             => 'nullable|string|max:100',
            'is_active'         => 'nullable|boolean',
            'is_recommended'    => 'nullable|boolean',
            'category_id'       => 'required|exists:product_categories,id',
            // main_image: gambar utama (1 file)
            'main_image'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            // images[]: gambar tambahan (multiple)
            'images'            => 'nullable|array',
            'images.*'          => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Product name is required.',
            'price.required'       => 'Price is required.',
            'stock.required'       => 'Stock is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists'   => 'Selected category does not exist.',
            'main_image.image'     => 'Main image must be a valid image file.',
            'main_image.max'       => 'Main image must be under 2MB.',
            'images.*.image'       => 'Each additional image must be a valid image file.',
            'images.*.max'         => 'Each image must be under 2MB.',
        ];
    }

    // Auto-generate slug dari name jika tidak diisi
    protected function prepareForValidation(): void
    {
        if (!$this->filled('slug') && $this->filled('name')) {
            $this->merge([
                'slug' => \Illuminate\Support\Str::slug($this->name),
            ]);
        }
    }
}