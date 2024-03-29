<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FavoriteRequest;
use App\Models\Favorite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $favorites = Favorite::where('user_id', Auth::user()->id)->get()->toArray();
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
        $favorite = new Favorite($request->all());
        $favorite->save();

        $newFavorite = Favorite::where('id', $favorite->id)->get()->first()->toArray();

        return response()->json(['favorite' => $newFavorite, 'message' => 'Quote has been saved as favorite.']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Favorite $favorite
     * @return \Illuminate\Http\Response
     */
    public function destroy($favoriteId)
    {
        $favorite = Favorite::find($favoriteId);
        $favorite->delete();
        return response()->json(['message' => 'Quote Kanye West successfully deleted.']);
    }
}
