<?php

namespace App\Http\Requests\Dashboard\Profile;

use App\Models\User; //panggil model user nya
use Illuminate\Foundation\Http\FormRequest;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Validation\Rule;

use Auth;

class UpdateDetailUserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        //buat jadi true supaya bisa menggunakan fungsi auth
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'name' => [
                'required', 'string', 'max:255'
            ],
            'email' => [
                'required', 'string', 'max:255', 'email', Rule::unique('users')->where('id', '<>', Auth::user()->id)
            ]
        ];
    }
}
