<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProfileRequest extends FormRequest
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
        $profileId = $this->user()->profile ? $this->user()->profile->id : 0;
        
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,'.$this->user()->id,
            'phone' => 'required|string|max:255|unique:profiles,phone,'.$profileId,
            'profile_picture'=>'nullable|image|max:1024',
            'resume'=>'nullable|file|max:1024',
            'password'=>'nullable|confirmed|min:6',
            'password_confirmation'=>'nullable|min:6',
            'address'=>'nullable|string|max:255',
            'bio'=>'nullable|string|max:255',
        ];
    }
}