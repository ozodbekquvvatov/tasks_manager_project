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
            'task_name' => 'required|string|max:255',
            'priority' => 'required|in:low,medium,high', // Valid priority values
            'status' => 'required|in:not-started,in-progress,completed',
            'due_date' => 'required|date|before_or_equal:today', // Date must be today or after
            // 'status' => 'required|string|in:not-started,in-progress,completed',    #hali bitmgaan 
            'description' => 'nullable|string',
            'image' => 'nullable|file|mimes:png,jpg,gif|max:10240', // 10MB max for imagee ixtiyoriy bo‘lishi mumkin, faqat rasm formatlarida va maksimal 2MB
        ];
    }
}
