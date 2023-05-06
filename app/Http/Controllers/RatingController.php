<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; // Chamando a model User
use App\Models\Rating; // Chamando a model Rating
use App\Http\Requests\Rating\RatingStoreRequest; // Chamando o Form Request (Para validação)
use App\Http\Requests\Rating\RatingRatingByUserRequest; // Chamando o Form Request (Para validação)


use Illuminate\Support\Facades\Auth; // Para pegar o usuário que está logado


class RatingController extends Controller
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
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RatingStoreRequest $request)
    {
        if (Auth::user()->id != $request->user_id) return false;
        $user = User::find($request->user_id);

        $rating = Rating::updateOrCreate(
            ['user_id' => $user->id, 'movie_id' => $request->movie_id],
            ['rating' => $request->rating, 'review' => $request->review]
        );

        return response()->json(['user' => $user->with('userProfile')->where('id', $user->id)->get()], 200);

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
        $rating = Rating::where('id', $id)->where('user_id', Auth::user()->id);

        if ($rating) {
            $rating->delete();
            return response()->json(['rating' => 'sucesso'], 200);
        }

        return response()->json(['erro' => 'Essa crítica não existe'], 404);
    }

    public function ratingByUser(RatingRatingByUserRequest $request) {

        if (Auth::user()->id != $request->user_id) return false;
        $user = User::find($request->user_id);

        $rating = Rating::where('user_id', $user->id)->where('movie_id', $request->movie_id)->get();

        return response()->json(['rating' => $rating], 200);
    }

    public function ratingsByMovie($id) {

        $ratings = Rating::where('movie_id', $id)->with('user')->orderBy('created_at', 'desc')->get();

        if ($ratings)
            return response()->json(['ratings' => $ratings], 200);

        return response()->json(['erro' => 'Esse filme ainda não possui críticas. Que tal você ser o primeiro(a) ?'], 404);

    }

    public function ratingsAverageUsers(Request $request) {

        // Get the movie ratings and calculate the average
        $ratings = Rating::where('movie_id', $request->movie_id)->select('rating')->get()->toArray();

        $array_ratings = [];

        foreach ($ratings as $rating) {
            array_push($array_ratings, $rating['rating']);
        }

        $media = 0;

        if (count($array_ratings) > 0)
            $media = array_sum($array_ratings) / count($array_ratings);


        // Count the number of ratings
        $ratings_count = Rating::where('movie_id', $request->movie_id)->count();


        return response()->json(['average' => $media, 'ratings_count' => $ratings_count], 200);
    }

    public function lastRatings() {

        // Get the movie ratings and calculate the average
        $ratings = Rating::orderBy('id','desc')->take(10)->with('user')->get();
        return response()->json(['ratings' => $ratings], 200);
    }
}
