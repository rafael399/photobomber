<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Album;
use Illuminate\Http\Request;

class ListAlbumsController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $albums = Album::where('user_id', $user['id'])->get();

        return $albums;
    }
}
