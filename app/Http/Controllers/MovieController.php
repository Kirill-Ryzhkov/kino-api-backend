<?php

namespace App\Http\Controllers;

use App\Models\FavoriteMovie;
use Illuminate\Support\Facades\Auth;

class MovieController extends Controller
{
    /**
     * method: POST
     * description: toggle favorite movies
     * api: /api/favorite/toggle
     * params: integer id
     * response: JsonResponse
     */
    public function toggleFavorite($id)
    {
        $favorite = FavoriteMovie::firstOrCreate([
            'movie_id' => $id,
            'user_id' => Auth::user()->id
        ]);
        if ($favorite->wasRecentlyCreated) {
            $result = $favorite;
        } else {
            $result['deleted'] = $favorite->delete();
        }
        return response()->json($result);
    }

    /**
     * method: GET
     * description: list favorite movies
     * api: /api/favorite
     * params:
     * response: JsonResponse
     */
    public function listFavorite() 
    {
        return response()->json(FavoriteMovie::where('user_id', Auth::user()->id)->get());
    }
}
