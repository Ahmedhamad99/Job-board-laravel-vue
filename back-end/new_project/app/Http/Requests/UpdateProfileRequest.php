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
    return [
        'name' => 'sometimes|string|max:255',
        'email' => 'sometimes|email|max:255|unique:users,email,' . $this->user()->id,
        'phone' => 'sometimes|string|max:255|unique:users,phone,' . $this->user()->id,
       //'email' => 'sometimes|email|max:255',
       //'phone' => 'sometimes|string|max:255',

        'profile_picture' => 'nullable|image|max:1024',
        'resume' => 'nullable|file|max:1024',
        'password' => 'nullable|confirmed|min:6',
        'password_confirmation' => 'nullable|min:6',
        'address' => 'nullable|string|max:255',
        'bio' => 'nullable|string|max:255',
    ];
}

}
