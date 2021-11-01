<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Album;
use Illuminate\Http\Request;

class GetAlbumPhotosController extends Controller
{
    public function __invoke(Request $request, $albumId)
    {
        /** @var User $user */
        $user = $request->user();

        $album = Album::where('id', $albumId)
            ->where('user_id', $user['id'])
            ->first();

        $photos = $album->photos()->get();

        return $photos;
    }
}
