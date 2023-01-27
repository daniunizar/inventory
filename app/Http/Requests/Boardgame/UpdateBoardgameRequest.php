<?php

namespace App\Http\Requests\Boardgame;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;

class UpdateBoardgameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $user = User::findOrFail(Auth::id());
        return $user->boardgames()->where('boardgames.id',$this->id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'id'            => 'required|integer|exists:boardgames,id',
            'label'         => 'required|string',
            'description'   => 'sometimes|nullable|string',
            'editorial'     => 'sometimes|nullable|string',
            'min_players'   => 'sometimes|nullable|integer|min:1',
            'max_players'   => 'sometimes|nullable|integer|min:1',
        ];
    }

    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            if(!empty($this->min_players) && !empty($this->max_players)){
                if ($this->min_players > $this->max_players) {
                    $validator->errors()->add('min_players', 'El número mínimo de jugadores no puede ser superior al número máximo de jugadores');
                }
            }
        });
    }
    public function failedValidation(Validator $validator) {
		throw new HttpResponseException(response()->json([
			'success' => false,
			'message' => '',
			'data'    => $validator->errors()
		], 422));
	}

}
   