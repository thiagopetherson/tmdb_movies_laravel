<?php

namespace App\Http\Requests\Rating;

use Illuminate\Foundation\Http\FormRequest;

class RatingStoreRequest extends FormRequest
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
     * @return array
     */    

    // Regras de validações das requisições
    public function rules() {               
        return [ 
            'movie_id' => 'required',
            'rating' => 'required',
            'review' => 'required',
            'user_id' => 'required|exists:App\Models\User,id'
        ];
    }

    // Mensagens de resposta das validações da requisição
    public function messages()
    {
        return [
            'movie_id.required' => 'O campo movie_id é obrigatório',
            'rating.required' => 'O campo de avaliação é obrigatório',
            'review.required' => 'O campo de crítica é obrigatório',
            'user_id.required' => 'O campo user_id é obrigatório',
            'exists' => 'Houve um problema com o seu usuário'                       
        ];
    }
}