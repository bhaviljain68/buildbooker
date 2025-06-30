<?php

use Illuminate\Http\UploadedFile;
use Intervention\Image\Laravel\Facades\Image;

if (! function_exists('processLogoImage')) {
    /**
     * Process and upload organisation logo
     *
     * @return string|bool Path of saved file or false on failure
     *
     * @throws Exception
     */
    function processLogoImage(UploadedFile $file, string $organisationName, string $folder): bool|string
    {
        try {
            // Generate snake_case filename with timestamp
            $fileName = Str::slug($organisationName).'_'.now()->timestamp.'.webp';

            // Define storage path
            $directory = storage_path('app/public/'.$folder.'/');
            $storagePath = $directory.$fileName;

            // Ensure directory exists
            if (! File::isDirectory($directory)) {
                File::makeDirectory($directory, 0755, true);
            }
            // Load image using Intervention
            $image = Image::read($file)->scaleDown(null, 250);

            // Convert to WebP if not already
            if ($file->getClientOriginalExtension() !== 'webp') {
                $image->toWebp(60); // Quality set to 60
            }

            // Save the image to the storage path
            $image->save($storagePath);

            // Return the relative path for storage access
            return 'storage/'.$folder.'/'.$fileName;
        } catch (Exception $e) {
            // Log the error if needed
            throw new Exception('Error Processing Logo: '.$e->getMessage());
        }
    }
}
