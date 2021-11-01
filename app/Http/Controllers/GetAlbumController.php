<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Album;
use Illuminate\Http\Request;

class GetAlbumController extends Controller
{
    public function __invoke(Request $request, $albumId)
    {
        /** @var User $user */
        $user = $request->user();

        $album = Album::select('id', 'title', 'description', 'layout_type')
            ->where('id', $albumId)
            ->where('user_id', $user['id'])
            ->first();

        return $album;
    }
}
