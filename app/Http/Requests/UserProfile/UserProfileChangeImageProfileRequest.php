<?php

namespace App\Http\Requests\UserProfile;

use Illuminate\Foundation\Http\FormRequest;

class UserProfileChangeImageProfileRequest extends FormRequest
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
    public function rules()
    {
      return [
        'id' => 'required',
        'avatar' => 'file|required'
      ];
    }

    // Mensagens de resposta das validações da requisição
    public function messages()
    {
        return [
            'file' => 'É obrigatório que seja inserido uma imagem válida',
            'required' => 'O campo de :attribute é obrigatório',
        ];
    }
}