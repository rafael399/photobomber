<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Photo;
use Illuminate\Http\Request;

class ListPhotosController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        $photos = Photo::select('id', 'path')->where('user_id', $user['id'])->get();

        return $photos;
    }
}
