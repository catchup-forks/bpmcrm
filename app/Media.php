<?php

namespace App;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\Models\Media as BaseMedia;

final class Media extends BaseMedia
{
    /**
     * Updates the Media with a new file
     *
     * @param Models\Media $file
     */
    public function updateFile(UploadedFile $newFile, \app\Models\Media $file): void
    {
        $originalFilePath = $file->id . '/' . $file->file_name;
        $newFileName = $this->sanitizeFileName($newFile->getClientOriginalName());
        $newFilePath = $file->id . '/' . $newFileName;

        Storage::disk('public_html')->delete($originalFilePath);
        Storage::disk('public_html')->put($newFilePath, $newFile);

        $file->file_name = $newFileName;
        $file->name = pathinfo($newFileName, PATHINFO_FILENAME);
        $file->mime_type = $newFile->getMimeType();
        $file->size = filesize($newFile->path());
        $file->save();
    }

    /**
     * Removes from file name non accepted characters
     *
     *
     */
    private function sanitizeFileName(string $fileName): string
    {
        return str_replace(['#', '/', '\\'], '-', $fileName);
    }
}