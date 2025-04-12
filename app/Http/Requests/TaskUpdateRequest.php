<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TaskUpdateRequest extends FormRequest
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
            'task_name' => 'required|string|max:255',  // task_name kerakli va maksimal 255 belgidan iborat
            'priority' => 'required|integer|in:1,2,3', // priority 1, 2 yoki 3 bo'lishi kerak
            'due_date' => 'required|date|after_or_equal:today', // due_date hozirgi kundan keyingi sana bo'lishi kerak
            'description' => 'nullable|string|max:500', // description ixtiyoriy, lekin maksimal 500 belgidan iborat bo'lishi kerak
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // image ixtiyoriy bo‘lishi mumkin, faqat rasm formatlarida va maksimal 2MB
        ];
    }
}
