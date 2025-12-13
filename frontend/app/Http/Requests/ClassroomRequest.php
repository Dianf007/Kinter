<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ClassroomRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Validasi akses bisa ditambah sesuai kebutuhan
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
            'name' => 'required|string|max:100',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
            'status' => 'required|in:aktif,non-aktif',
            'school_id' => 'required|exists:schools,id',
            'teacher_id' => 'nullable|exists:teachers,id',
            'teachers' => 'nullable|array',
            'teachers.*' => 'exists:teachers,id',
        ];
    }
}
