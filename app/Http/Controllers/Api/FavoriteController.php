<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteRequest;
use App\Models\Favorite;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = Favorite::get()->toArray();
        return response()->json($favorites);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\FavoriteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(FavoriteRequest $request)
    {
        $school = new Favorite($request->all());
        $school->save();

        $newFavorite = Favorite::where('id', $school->id)->get()->first()->toArray();

        return response()->json(['school' => $newFavorite, 'message' => 'La escuela se guardo exitosamente.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Favorite $favorite
     * @return \Illuminate\Http\Response
     */
    public function destroy(Favorite $favorite)
    {
        $favorite->delete();
        return response()->json(['message' => 'La cita de Kanye West se elimino exitosamente.']);
    }
}
