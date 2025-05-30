<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDatabaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; //jw:note changed from default 'false' to avoid the "403 This action is unauthorized." error
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //jw:todo add for rules here PM: Is this used at all???
            'title' => 'required|unique:databases,title|max:255',
            'description' => 'required|max:255',
            'user_id' => '',
        ];
    }
    /*
     * https://dev.to/secmohammed/laravel-form-request-tips-tricks-2p12
     */
    public function validationData()
    {
        return array_merge($this->all(), [
            'user_id' => $this->user()->id
        ]);
    }
}
