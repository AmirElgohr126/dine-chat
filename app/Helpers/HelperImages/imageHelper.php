<?php

use Illuminate\Support\Facades\Storage;

/**
 * Stores an uploaded file in the specified type or directory and disk.
 *
 * @param \Illuminate\Http\UploadedFile $file The file to be stored.
 * @param string $type The directory where the file should be stored.
 * @param string $dirver The driver or disk where the file should be stored.
 *
 * @return string Returns the path at which the file was stored.
 */
function storeFile($file, $type, $dirver): string
{
    $fileName = rand(100000, 999999) . time() . $file->getClientOriginalName();
    $path = $file->storeAs($type, $fileName, $dirver);
    return $path;
}

/**
 * Deletes old files associated with an Eloquent model attribute and updates it with a new file.
 *
 * @param \Illuminate\Http\UploadedFile $file The new file to be stored.
 * @param \Illuminate\Database\Eloquent\Model $model An Eloquent model instance.
 * @param string $attributename The attribute name in the Eloquent model that stores the file path.
 * @param string $diskstore The disk where the new file should be stored.
 * @param string $diskdelete The disk from which the old file should be deleted.
 *
 * @return bool Returns true if a file was provided and the operation was successful, otherwise returns false.
 */
function updateAndDeleteFile($file, $model, string $attributename, string $diskstore, string $type, string $diskdelete): bool
{
    if ($file) {
        $old = $model->$attributename;
        $path = storeFile($file, $type, $diskstore);
        $update = $model->update([$attributename => $path]);
        if ($old) {
            Storage::disk($diskdelete)->delete($old);
        }
        return true;
    }
    return false;
}


function retriveMedia(): string
{
    if (env('APP_URL') == 'http://127.0.0.1:8000') {
        $path = env('APP_URL') . '/';
    } else {
        $path = env('APP_URL') . '/public/storage/';
    }
    return $path;
}
?>
