<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ExamSetDataRequest extends FormRequest
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
            'name' => 'sometimes|required|string',
            'description' => 'nullable|string',
            'visibility' => 'sometimes|required|integer',
            'multi_lesson_exam' => 'sometimes|boolean',
            'new_lesson' => 'nullable|string|max:255',
            'remove_lesson' => 'nullable|integer|exists:lessons,id',
        ];
    }
}
