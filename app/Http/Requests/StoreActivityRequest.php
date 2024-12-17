<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActivityRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    public function prepareForValidation()
    {
        return $this->merge([
            'student_id' => 1,
            'status' => 0
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'student_id' => ['exists:students,id'],
            'activity_category_id' => ['required', 'exists:activity_categories,id'],
            'level_id' => ['required', 'exists:levels,id'],
            'award_id' => ['required', 'exists:awards,id'],
            'award_type' => ['required', 'in:1,2'],
            'name' => ['required', 'string', 'max:255'],
            'place' => ['required', 'string', 'max:255'],
            'date' => ['required', 'date'],
            'photo' => ['required', 'mimes:jpg,jpeg,png', 'max:5000'],
            'file' => ['required', 'mimes:pdf', 'max:5000'],
            'description' => ['required'],
            'status' => ['in:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_id.exists' => 'Mahasiswa tidak terdaftar',
            'activity_category_id.required' => 'Kategori Kegiatan wajib dipilih',
            'activity_category_id.exists' => 'Kategori Kegiatan tidak terdaftar',
            'level_id.required' => 'Tingkat Kegiatan wajib dipilih',
            'level_id.exists' => 'Tingkat Kegiatan tidak terdaftar',
            'award_id.required' => 'Penghargaan wajib dipilih',
            'award_id.exists' => 'Penghargaan tidak terdaftar',
            'award_type.required' => 'Jenis Prestasi wajib dipilih',
            'award_type.in' => 'Jenis Prestasi tidak terdaftar',
            'name.required' => 'Nama Kegiatan wajib diisi',
            'name.max' => 'Nama Kegiatan maksimal 255 kata',
            'place.required' => 'Tempat Kegiatan wajib diisi',
            'place.max' => 'Tempat Kegiatan maksimal 255 kata',
            'date.required' => 'Tanggal Kegiatan wajib diisi',
            'date.date' => 'Tanggal Kegiatan tidak sesuai format',
            'photo.required' => 'Foto Kegiatan wajib upload',
            'photo.mimes' => 'Foto Kegiatan tidak sesuai format',
            'photo.max' => 'Foto Kegiatan maksimal 5 MB',
            'file.required' => 'Sertifikat wajib upload',
            'file.mimes' => 'Sertifikat tidak sesuai format',
            'file.max' => 'Sertifikat maksimal 5 MB',
            'description.required' => 'Deskripsi Kegiatan wajib diisi',
        ];
    }
}
