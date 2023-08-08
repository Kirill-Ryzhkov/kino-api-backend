<?php

namespace App\Http\Controllers;

use App\Models\FavoriteMovie;
use App\Services\ApiService;
use Illuminate\Http\Request;
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
    public function listFavorite(Request $request) 
    {
        $api = new ApiService();

        $api->validateLang($request);
        $favorite = implode(',', FavoriteMovie::where('user_id', Auth::user()->id)->get()->pluck('movie_id')->toArray());

        return response()->json($api->getMovieById($request, $favorite));
    }
}
