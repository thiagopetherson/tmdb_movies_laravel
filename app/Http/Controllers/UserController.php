<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User; // Chamando a model User
use App\Http\Requests\User\UserUpdateRequest; // Chamando o Form Request (Para validação)

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $users = User::orderBy('name')->with('userProfile')->get(); 
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
        $user = User::find($id);

        if ($user) {
            return response()->json(['user' => $user->with('userProfile')->where('id', $user->id)->get()], 200);
        }

        return response()->json(['erro' => 'Usuário inválido'], 403);
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserUpdateRequest $request, $id)
    {   
        // Pegando o id do usuário logado pelo backend, para comparar com o que vem do frontend
        $loggedUserId = Auth::user()->id;
        if ($loggedUserId != $id) return false;

        // Pegando o usuário daquele respectivo ID
        $user = User::find($loggedUserId);  
    
        if ($request->name) 
            $user->name  = $request->name;
      
        if ($user->save()) {       

            $gender = '';
            $array_gender = ['Feminino','Masculino','Outro Gênero'];
            if ($request->gender) {

                if (!in_array($request->gender, $array_gender))
                    return response()->json(['erro' => 'Gênero inválido'], 403);

                $gender = $request->gender;
            }                

            $birth_date = '';
            if ($request->birth_date) 
                $birth_date = $request->birth_date;

            $telephone = '';
            if ($request->telephone) 
                $telephone = $request->telephone;
            
            $profession = '';
            if ($request->profession) 
                $profession = $request->profession;

            $profile_phrase = '';
            if ($request->profile_phrase) 
                $profile_phrase = $request->profile_phrase;

            $biography = '';
            if ($request->biography) 
                $biography = $request->biography;
            
            $user->userProfile()->update([
                'surname' => $request->surname,
                'alias' => $request->alias,
                'gender' => $gender,
                'birth_date' => $birth_date,
                'telephone' => $telephone,
                'profession' => $profession,
                'profile_phrase' => $profile_phrase,
                'biography' => $biography
            ]);
        } 

        return response()->json(['user' => $user->with('userProfile')->where('id', $user->id)->get()], 200);
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

    public function usersListSearch(Request $request) 
    {       
        $users = User::orderBy('name')->where('name','LIKE','%'.$request->text.'%')->with('userProfile')->get();  
        return response()->json(['users' => $users], 200);
    }
}
