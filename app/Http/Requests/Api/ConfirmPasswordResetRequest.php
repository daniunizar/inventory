<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rules\Password;

/**
 * This class is relationship about validation when an User want update his password from a Reset Password Mail
 */
class ConfirmPasswordResetRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules() {
        return [
            'token' => 'required',
            'password' => [
                'required',
                'confirmed',
                Password::min(6) // Debe tener por lo menos 6 caracteres
                    ->mixedCase() // Debe tener mayúsculas + minúsculas
                    ->letters() // Debe incluir letras
                    ->numbers() // Debe incluir números
                    ->symbols(), // Debe incluir símbolos
                ],
        ];
    }

    public function failedValidation(Validator $validator) {
		throw new HttpResponseException(response()->json([
			'success' => false,
			'message' => '',
			'data'    => $validator->errors()
		], 422));
	}
}