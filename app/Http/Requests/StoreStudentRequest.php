<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStudentRequest extends FormRequest
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
            'student_number' => ['required', 'numeric', 'unique:students,student_number,except,id'],
            'name' => ['required', 'string', 'max:255'],
            'program' => ['required', 'in:1,2'],
            'enrollment_year' => ['required', 'numeric'],
            'birth_date' => ['required', 'date'],
            'address' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'student_number.required' => 'NIM wajib diisi',
            'student_number.numeric' => 'NIM berupa angka',
            'student_number.unique' => 'NIM sudah terdaftar',
            'name.required' => 'Nama wajib diisi',
            'name.max' => 'Nama maksimal 255 kata',
            'program.required' => 'Program Studi wajib diisi',
            'program.in' => 'Program Studi tidak ada dalam daftar',
            'enrollment_year.required' => 'Angkatan wajib diisi',
            'enrollment_year.numeric' => 'Angkatan berupa angka',
            'birth_date.required' => 'Tanggal Lahir wajib diisi',
            'birth_date.date' => 'Tanggal Lahir berupa tanggal',
            'address.required' => 'Alamat wajib diisi',
        ];
    }
}
