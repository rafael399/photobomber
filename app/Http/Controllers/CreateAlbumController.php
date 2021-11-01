<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use Illuminate\Http\Request;

class CreateAlbumController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $album = $user->albums()->create([
            'title' => $request['title'],
            'description' => $request['description'],
            'layout_type' => intval($request['layout']),
        ]);

        return redirect()->intended('/album-dashboard?albumId=' . $album['id']);
    }
}
