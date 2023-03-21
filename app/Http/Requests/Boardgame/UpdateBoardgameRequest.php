<?php

namespace App\Http\Requests\Boardgame;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
        Log::debug("Pintando boardgame_id");
        Log::debug($this->boardgame_id);
        return $user->boardgames()->where('boardgames.id',$this->boardgame_id)->exists();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            // 'id'            => 'required|integer|exists:boardgames,id',
            'label'         => 'required|string',
            'description'   => 'sometimes|nullable|string',
            'editorial'     => 'nullable|string',
            'min_players'   => 'nullable|integer|min:1',
            'max_players'   => 'nullable|integer|min:1',
            'min_age'       => 'nullable|integer|min:0',
            'max_age'       => 'nullable|integer|max:99',
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
            //check players range
            if(!empty($this->min_players) && !empty($this->max_players)){
                if (!$this->isValidPlayersRange()) {
                    $validator->errors()->add('max_players', 'El número máximo de jugadores no puede ser inferior al número mínimo de jugadores');
                }
            }
            //check age range
            if(!empty($this->min_age!=null) && !empty($this->max_age!=null)){
                if(!$this->isValidAgeRange()){
                    $validator->errors()->add('max_age', 'La edad máxima de juego recomendada no puede ser inferior a la edad mínima de juego recomendada');
                }
            }
        });
    }
    public function isValidPlayersRange():bool{
        $is_valid = true;
            if($this->min_players > $this->max_players){
                $is_valid = false;
            } 
        return $is_valid;
    }
    public function isValidAgeRange():bool{
        $is_valid = true;
            if($this->min_age > $this->max_age){
                $is_valid = false;
            }
        return $is_valid;
    }
    public function failedValidation(Validator $validator) {
		throw new HttpResponseException(response()->json([
			'success' => false,
			'message' => '',
			'data'    => $validator->errors()
		], 422));
	}
}
   