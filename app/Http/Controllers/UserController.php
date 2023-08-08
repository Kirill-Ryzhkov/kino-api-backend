<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    /**
     * method: GET
     * description: get autorized user
     * api: /api/user
     * params:
     * response: JsonResponse
     */
    public function get()
    {
        return response()->json(['user' => Auth::user()]);
    }

    /**
     * method: PUT
     * description: update autorized user
     * api: /api/user/update
     * params: Request $request(string name, string email, string password)
     * response: JsonResponse
     */
    public function update(Request $request)
    {
        $request->validate([
            'name' => ['string'],
            'email' => ['email'],
            'password' => ['string']
        ]);
        $user = User::find(Auth::user()->id);

        $data = $request->only(['name', 'email', 'password']);

        if ($request->has('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->fill($data);
        $user->save();

        return response()->json($user->get());
    }

    /**
     * method: POST
     * description: logout
     * api: /api/user/logout
     * params:
     * response: void
     */
    public function logout()
    {
        Auth::guard('web')->logout();
    }
}
