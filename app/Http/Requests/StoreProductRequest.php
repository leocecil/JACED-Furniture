<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name'        => 'required|string|max:255',
            'description' => 'nullable|string',
            'length'      => 'required|numeric|min:0',
            'width'       => 'required|numeric|min:0',
            'height'      => 'required|numeric|min:0',
            'unit'        => 'required|string|in:cm,m,inch',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|integer|min:0',
            'low_stock'   => 'nullable|integer|min:0',
            'label'       => 'nullable|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            
            // PERBAIKAN: Diubah menjadi required dan minimal harus ada 1 file gambar yang diunggah
            'images'      => 'required|array|min:1',
            'images.*'    => 'image|mimes:jpg,jpeg,png,webp|max:2048',
        ];
    }

    public function messages(): array
    {
        return [
            'name.required'        => 'Product name is required.',
            'length.required'      => 'Length is required.',
            'width.required'       => 'Width is required.',
            'height.required'      => 'Height is required.',
            'unit.required'        => 'Unit is required.',
            'price.required'       => 'Price is required.',
            'stock.required'       => 'Stock is required.',
            'category_id.required' => 'Please select a category.',
            'category_id.exists'   => 'Selected category does not exist.',
            
            // PERBAIKAN: Pesan error kustom saat array images kosong atau tidak dikirim
            'images.required'      => 'Please upload at least one product image.',
            'images.min'           => 'You must upload at least one product image.',
            'images.*.image'       => 'Each file must be a valid image.',
            'images.*.max'         => 'Each image must be under 2MB.',
        ];
    }
}