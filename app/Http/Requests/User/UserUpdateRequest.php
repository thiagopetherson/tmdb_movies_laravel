<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
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

        //Pegando o id que veio via GET na requisição
        $id = $this->segment(3);

        return [ 
            'name' => 'required|string|min:2|max:100',
            'surname' => 'required|string|min:2|max:200',
            'alias' => 'required|min:2|max:50|unique:user_profiles,alias,'.$id,
            'birth_date' => 'min:10|max:10|nullable',
            'telephone' => 'min:15|max:15|nullable',
            'profession' => 'max:100',
            'profile_phrase' => 'max:150',
            'biography' => 'max:2500',
            //'alias' => 'required|unique:user_profiles,alias,{$id},user_id',
        ];
    }

    // Mensagens de resposta das validações da requisição
    public function messages()
    {
        return [
            'name.required' => 'O campo nome é obrigatório',
            'name.min' => 'O campo de nome deve ter no mínimo 2 caracteres',
            'name.max' => 'O campo de nome deve ter no máximo 100 caracteres',
            'surname.required' => 'O campo sobrenome é obrigatório',
            'surname.min' => 'O campo de sobrenome deve ter no mínimo 2 caracteres',
            'surname.max' => 'O campo de sobrenome deve ter no máximo 200 caracteres',
            'alias.required' => 'O campo usuário é obrigatório',
            'alias.min' => 'O campo de usuário deve ter no mínimo 2 caracteres',
            'alias.max' => 'O campo de usuário deve ter no máximo 200 caracteres', 
            'alias.unique' => 'Esse Usuário já está sendo utilizado!',
            'birth_date.min' => 'O campo de data de nascimento deve ser preenchido corretamente',
            'birth_date.max' => 'O campo de data de nascimento deve ser preenchido corretamente',
            'telephone.min' => 'O campo de telefone deve ser preenchido conforme o exemplo',
            'telephone.max' => 'O campo de telefone deve ser preenchido conforme o exemplo',
            'profession.max' => 'O campo de profissão deve ter no máximo 100 caracteres',
            'profile_phrase.max' => 'O campo de frase de perfil deve ter no máximo 150 caracteres',
            'biography.max' => 'O campo de biografia deve ter no máximo 2500 caracteres',
        ];
    }
}