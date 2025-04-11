<?php

namespace App\Http\Requests;

use App\Models\User;
use App\Models\Database;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateDatabaseRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
//        $id = $this->user()->id;
 //       $b = $this->user()->can('update');
  //      return $b;
        return true; //jw:note have to return true here, if we want to be authorized to update
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            //
            'title' => [
                'required',
                'max:255',
                Rule::unique('databases')->ignore($this->route('database')->id) /* jw:note this prevents an update from complaining about uniqueness with itself */
            ],
            'description' => 'required|max:255',
            'user_id' => 'required',
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
