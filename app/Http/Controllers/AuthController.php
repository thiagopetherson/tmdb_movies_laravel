<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Mail\SendMailUser; // Classe de Envio de Email (Criada)
use Illuminate\Support\Facades\Mail; // Importando a lib de envio de email

use App\Http\Requests\Auth\AuthRegisterRequest; // Chamando o Form Request (Para validação)

use Illuminate\Http\Request;

class AuthController extends Controller
{

    public function __construct () {
        $this->middleware('auth:api', ['except' => ['login','register']]);
    }

    public function login (Request $request) {

        $credenciais = $request->all(['email','password']);

        // Autenticação (email e senha)
        // O método attempt realiza uma tentativa de autenticação
        // Se a autenticação der certo, o método attempt retornará um token
        // Se a autenticação der errado, retornará false
        $token = auth('api')->attempt($credenciais);
        
        // Verificando a autenticação
        if ($token) {
            $loggedUserId = Auth::user()->id;
            $user = User::find($loggedUserId);          

            return response()->json(['user' => $user->with('userProfile')->where('id', $user->id)->get(), 'token' => $token], 200);
        } else {           
            return response()->json(['erro' => 'Email ou Senha inválidos'], 403);
        }
    }

    public function logout () {
        auth('api')->logout(); // Método de fazer o logout
        return response()->json(['msg' => "Logout efetuado com sucesso!"], 200);
    }

    public function refresh () {

        // Para o método refresh funcionar, é obrigatório que o client envie em token jwt na requisição
        $token = auth('api')->refresh();
        return response()->json(['token' => $token], 200);
  
    }

    public function me () {
        return response()->json(auth()->user());
    }


    public function register(AuthRegisterRequest $request)
    {  


        $arrayPalavras = ['Filmes','Cinema','Legal','Series','Senhas','Info'];
        $palavraAleatoria = array_rand($arrayPalavras, 1); // Pegando o índice de uma palavra do array        
        $rand = strval(rand(1000,9999));       
        $randomPassword = $arrayPalavras[$palavraAleatoria]."@".$rand; 


        $body = "<html>";
        $body .= '<body style="font-family:calibri; font-size:16px; text-align:justify;">';
        $body .= '<div style="background-color: rgba(0, 0, 0, 0.75);border:1px solid gray; color:rgb(55,55,55); padding:14px; font-size:16px;">';
        $body .= '<h2 style="color:white; font-weight: bold;"> Senha Provisória</h2>';
        $body .= '</div>';
        $body .= '<div style="background-color:rgb(230,230,230); color:rgb(55,55,55); padding:2%; font-family:verdana; font-size:16px; word-wrap: break-word; white-space: pre-wrap; white-space: -moz-pre-wrap;">';
        $body .= '<p>Você solicitou o cadastro na plataforma do <b>Top Movies</b>.<br>Foi gerada uma senha provisória. Após realizar o login, é recomendado que você altere a senha.<br>Sua senha: <b>'.$randomPassword.'</b></p><br>';
        $body .= '<p><b>OBS: </b>Não responda esse email</p>'; 
        $body .= '</div>';       
        $body .= '</body>';
        $body .= '</html>';


        $user = new User();
        $user->name = $request->name;       
        $user->email = $request->email;
        $user->password = bcrypt($randomPassword);
        $user->save();
               
        $user->userProfile()->create([
            'surname' => $request->surname,
            'alias' => $request->name.rand(1, 10000),
        ]);

        /*
        $credenciais = $request->only('email', 'password');
        
        $token = auth('api')->attempt($credenciais);

        // Verificando a autenticação
        if ($token) {
            return response()->json(['user' => $user->with('userProfile')->where('id', $user->id)->get(), 'token' => $token], 200);
        } else {
            // Erro 403 é o erro Forbidden. Um potencial login inválido (o 401 é o não autorizado)
            return response()->json(['erro' => 'Usuário ou Senha inválidos'], 403);
        }  
        */

        Mail::to($user->email)->send(new SendMailUser(array(
            'assunto' => 'Cadastro Top Movies TMDB',
            'titulo' => 'Top Movies TMDB - Criação de Usuário',
            'corpo' => $body
        ))); 

        return response()->json(['success' => 'Usuário cadastrado com sucesso!'], 200);

    }

}