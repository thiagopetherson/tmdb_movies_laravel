<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserProfile\UserProfileChangeImageProfileRequest;
use App\Models\User;
use App\Models\UserProfile;

use Illuminate\Support\Facades\Storage; // Do arquivo
use Image; // Do arquivo

use Illuminate\Support\Facades\Auth; // Para pegar o usuário que está logado

class UserProfileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function changeImageProfile(UserProfileChangeImageProfileRequest $request)
    {
        $user = User::find($request->id);

        if(!$user) {           
            return response()->json(['erro' => 'Usuário não encontrado!'], 403);
        }

        $file = $request->file('avatar');

        // Se informou o arquivo, retorna um boolean
        $fileExists = $request->hasFile('avatar');
        if(!$fileExists) {
            return response()->json(['error' => 'Não é um arquivo de imagem válido!'], 403);
        }

        // Retorna mime type do arquivo (Exemplo image/png)
        $mimeType = $request->avatar->getMimeType();

        $arrayMimeTypes = ['image/png','image/jpg','image/jpeg'];

        if(!in_array($mimeType,  $arrayMimeTypes))
        {
            return response()->json(['error' => 'Não é um arquivo de imagem válido!'], 403);
        }

        // Extensão do arquivo
        $extension = $request->avatar->getClientOriginalExtension();

        $arrayExtensions = ['PNG','JPG','JPEG','png','jpg','jpeg'];

        if(!in_array($extension, $arrayExtensions))
        {
            return response()->json(['error' => 'O arquivo não tem uma extensão válida!'], 403);
        }

        // Tamanho do arquivo
        $fileSize = $request->avatar->getSize();

        if($fileSize > 2097152) {
            return response()->json(['error' => 'O tamanho do arquivo ultrapassa o limite de 2MB!'], 403);
        }

        //Verificando se é um arquivo válido
        $isValid = $request->file('avatar')->isValid();

        if($isValid != 1) {
            return response()->json(['error' => 'Não é um arquivo de imagem válido!'], 403);
        }

        $imageName = md5($user->id) . '.jpg';
        //Movendo o arquivo da pasta temporária para storage (que tem um espelho em public)
        $upload = $request->avatar->storeAs('media/uploadsimagesprofile', $imageName, 'public');
        // $upload = $request->avatar->store('media/uploadsimagesprofile/'.$imageName,'public');

        $dataform['avatar'] = $upload;

        if($upload AND $user->userProfile()->update($dataform)) {
             return response()->json(['user' => $user->with('userProfile')->where('id', $user->id)->get()], 200);
        }

        return response()->json(['error' => 'A imagem não foi inserida. Tente novamente ou entre em contato com o suporte!'], 403);

    }
}
