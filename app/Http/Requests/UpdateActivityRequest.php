<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateActivityRequest extends FormRequest
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
            'student_id' => ['exists:students,id'],
            'activity_category_id' => ['required', 'exists:activity_categories,id'],
            'level_id' => ['required', 'exists:levels,id'],
            'award_id' => ['required', 'exists:awards,id'],
            'award_type' => ['required', 'in:1,2'],
            'name' => ['required', 'string', 'max:255'],
            'place' => ['required', 'string', 'max:255'],
            'start_date' => ['required', 'date'],
            'end_date' => ['required', 'date'],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:5000'],
            'file' => ['nullable', 'mimes:pdf', 'max:5000'],
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
            'start_date.required' => 'Tanggal Mulai wajib diisi',
            'start_date.date' => 'Tanggal Mulai tidak sesuai format',
            'end_date.required' => 'Tanggal Selesai wajib diisi',
            'end_date.date' => 'Tanggal Selesai tidak sesuai format',
            'photo.mimes' => 'Foto Kegiatan tidak sesuai format',
            'photo.max' => 'Foto Kegiatan maksimal 5 MB',
            'file.mimes' => 'Sertifikat tidak sesuai format',
            'file.max' => 'Sertifikat maksimal 5 MB',
            'description.required' => 'Deskripsi Kegiatan wajib diisi',
        ];
    }
}
