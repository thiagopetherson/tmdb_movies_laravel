<?php

namespace App\Http\Requests\Auth;

use Illuminate\Foundation\Http\FormRequest;

class AuthRegisterRequest extends FormRequest
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
            'name' => 'required|string|min:2|max:100',
            'surname' => 'required|string|min:2|max:200',
            'email' => 'required|email|max:100|unique:users'
            // 'password' => 'required',
            // 'confirm_password' => 'required|same:password'   
        ];
    }

    // Mensagens de resposta das validações da requisição
    public function messages()
    {
        return [
            'required' => 'O campo :attribute é obrigatório',
            'name.min' => 'O campo de nome deve ter no mínimo 2 caracteres',
            'name.max' => 'O campo de nome deve ter no máximo 100 caracteres',
            'surname.min' => 'O campo de sobrenome deve ter no mínimo 2 caracteres',
            'surname.max' => 'O campo de sobrenome deve ter no máximo 200 caracteres',
            'email.max' => 'O campo de :attribute deve ter no máximo 200 caracteres',
            'email.unique' => 'Esse :attribute já está cadastrado!',
            'email.email' => 'O campo de :attribute deve ter um endereço válido'
            // 'confirm_password.same' => 'As senhas devem ser iguais'
        ];
    }
}