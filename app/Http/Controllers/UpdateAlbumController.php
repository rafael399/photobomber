<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\Models\User;
use App\Models\Album;
use App\Models\Photo;
use Illuminate\Http\Request;

class UpdateAlbumController extends Controller
{
    public function __invoke(Request $request)
    {
        /** @var User $user */
        $user = $request->user();

        // Retrieve the album
        $album = Album::select('id', 'title', 'description')
            ->where('id', $request['albumId'])
            ->where('user_id', $user['id'])
            ->first();

        // Update album information
        $album->title = $request['title'];
        $album->description = $request['description'];
        $album->layout_type = $request['layout'];
        $album->save();

        // Get the selected photos from user selection
        $selectedPhotos = $request['selectedPhotosId'];

        // Get actual photos of the album
        $actualPhotos = $album->photos()->get();
        $actualPhotosIds = [];

        // Insert photo IDs to actualPhotosIds array
        foreach ($actualPhotos as $photo) {
            array_push($actualPhotosIds, $photo['id']);
        }

        // Get array of photo IDs to add into the album
        $photosToInsert = array_diff($selectedPhotos, $actualPhotosIds);

        // Get array of photo IDs to remove from the album
        $photosToRemove = array_diff($actualPhotosIds, $selectedPhotos);

        // Insert photos into the album
        foreach ($photosToInsert as $photoId) {
            $photo = Photo::where('id', $photoId)->first();

            $album->photos()->save($photo);
        }

        // Remove photos from the album
        foreach ($photosToRemove as $photoId) {
            $photo = Photo::where('id', $photoId)->first();

            $album->photos()->detach($photo);
        }

        return redirect()->intended('/album-dashboard?albumId=' . $album['id']);
    }
}
