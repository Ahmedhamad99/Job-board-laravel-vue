<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class JobRequest extends FormRequest
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
            'employer_id' => ['required'],
            'title'=>['required','string','max:255', 'min:10'],
            'description'=>['required','string','max:255', 'min:15'],
            'skills'=> ['string'],
            'salary_min' => ['nullable'],
            'salary_max' => ['nullable'],
            'location'=>['required','string','max:255', 'min:4'],
            'category_id'=>['required'],
        ];
    }
}