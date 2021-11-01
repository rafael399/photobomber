## Photobomber implementation notes

I've had a lot of dificulty with this project as I've never programmed with PHP or Vue before (I didn't even know what `compose` was before starting this challenge and didn't know how to install PHP 😅)

### Gallery
I've had some trouble trying to show the pictures in screen because the 'storage/photos' file wasn't inside the 'public' files, but resolved it adding `public_path('photos') => storage_path('app/photos')` to the 'links' part of the filesystems so I could run `php artisan storage:link` to use the './photos/...' as the src of my images

After that, I've implemented a Toast to show a success/error message when loading the gallery photos and/or uploading a new photo

### Create album
While implementing the CreateAlbum page, I noticed there's no 'layout' field in the database, so I created a migration to add this column into the table

I also was thinking on creating one controller that would have the "create", "list", etc methods for each Model I was going to use, but I saw that the project had one controller for each specific method (like UploadPhotoController instead of a create method inside a PhotoController for example), so I decided to follow this pattern

[comment]: <> (Have fun and be creative)
