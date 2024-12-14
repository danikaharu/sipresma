<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAwardRequest extends FormRequest
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
            'activity_category_id' => ['required', 'exists:activity_categories,id'],
            'level_id' => ['required', 'exists:levels,id'],
            'name' => ['required', 'string', 'max:255'],
            'point' => ['nullable', 'numeric'],
        ];
    }

    public function messages(): array
    {
        return [
            'activity_category_id.required' => 'Kategori Kegiatan wajib dipilih',
            'activity_category_id.exists' => 'Kategori Kegiatan tidak terdaftar',
            'level_id.required' => 'Tingkat Kegiatan wajib dipilih',
            'level_id.exists' => 'Tingkat Kegiatan tidak terdaftar',
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 255 kata',
            'point.numeric' => 'Poin berupa angka',
        ];
    }
}
