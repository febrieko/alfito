<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Resize and store an uploaded image.
     * Automatically scales down if dimensions exceed maxWidth or maxHeight,
     * corrects EXIF orientation, and saves as an optimized WebP (or JPEG fallback).
     *
     * @param  string  $directory  Folder inside storage/app/public (e.g. 'tours')
     * @param  int  $maxWidth  Maximum allowed width (default 1600px)
     * @param  int  $maxHeight  Maximum allowed height (default 1200px)
     * @param  int  $quality  Compression quality (default 85%)
     * @return string Relative web URL starting with /storage/...
     */
    public function resizeAndStore(
        UploadedFile $file,
        string $directory = 'tours',
        int $maxWidth = 1600,
        int $maxHeight = 1200,
        int $quality = 85
    ): string {
        $realPath = $file->getRealPath();

        // If file doesn't exist or isn't a valid image, fallback to standard upload
        if (! $realPath || ! file_exists($realPath)) {
            $path = $file->store($directory, 'public');

            return '/storage/'.ltrim($path, '/');
        }

        $imageInfo = @getimagesize($realPath);
        if ($imageInfo === false || ! extension_loaded('gd')) {
            $path = $file->store($directory, 'public');

            return '/storage/'.ltrim($path, '/');
        }

        [$origWidth, $origHeight, $imageType] = $imageInfo;

        // Load image resource based on mime / type
        $srcImage = match ($imageType) {
            IMAGETYPE_JPEG => @imagecreatefromjpeg($realPath),
            IMAGETYPE_PNG => @imagecreatefrompng($realPath),
            IMAGETYPE_WEBP => function_exists('imagecreatefromwebp') ? @imagecreatefromwebp($realPath) : false,
            IMAGETYPE_GIF => @imagecreatefromgif($realPath),
            default => false,
        };

        if ($srcImage === false) {
            $path = $file->store($directory, 'public');

            return '/storage/'.ltrim($path, '/');
        }

        // Auto-orient JPEG based on EXIF if available
        if ($imageType === IMAGETYPE_JPEG && function_exists('exif_read_data')) {
            $srcImage = $this->autoOrient($realPath, $srcImage);
            $origWidth = imagesx($srcImage);
            $origHeight = imagesy($srcImage);
        }

        // Calculate new dimensions while preserving aspect ratio
        $targetWidth = $origWidth;
        $targetHeight = $origHeight;

        if ($origWidth > $maxWidth || $origHeight > $maxHeight) {
            $ratio = min($maxWidth / $origWidth, $maxHeight / $origHeight);
            $targetWidth = max(1, (int) round($origWidth * $ratio));
            $targetHeight = max(1, (int) round($origHeight * $ratio));
        }

        // Create canvas for resized image
        $destImage = imagecreatetruecolor($targetWidth, $targetHeight);

        // Preserve transparency for PNG and WebP
        imagealphablending($destImage, false);
        imagesavealpha($destImage, true);

        // Resample with high quality interpolation
        imagecopyresampled(
            $destImage,
            $srcImage,
            0,
            0,
            0,
            0,
            $targetWidth,
            $targetHeight,
            $origWidth,
            $origHeight
        );

        // Prepare destination directory using configured public disk
        $disk = Storage::disk('public');
        $cleanDir = trim($directory, '/');
        if (! $disk->exists($cleanDir)) {
            $disk->makeDirectory($cleanDir);
        }
        $targetDir = $disk->path($cleanDir);

        $filename = Str::random(24);

        if (function_exists('imagewebp')) {
            $filename .= '.webp';
            $fullPath = $targetDir.DIRECTORY_SEPARATOR.$filename;
            imagewebp($destImage, $fullPath, $quality);
        } else {
            $filename .= '.jpg';
            $fullPath = $targetDir.DIRECTORY_SEPARATOR.$filename;
            imagejpeg($destImage, $fullPath, $quality);
        }

        // Free memory
        imagedestroy($destImage);
        imagedestroy($srcImage);

        return '/storage/'.trim($directory, '/').'/'.$filename;
    }

    /**
     * Correct orientation for JPEG images based on EXIF Orientation metadata.
     *
     * @param  \GdImage  $image
     * @return \GdImage
     */
    private function autoOrient(string $filePath, $image)
    {
        try {
            $exif = @exif_read_data($filePath);
            if (! empty($exif['Orientation'])) {
                switch ($exif['Orientation']) {
                    case 3:
                        $rotated = imagerotate($image, 180, 0);
                        if ($rotated !== false) {
                            imagedestroy($image);
                            $image = $rotated;
                        }
                        break;
                    case 6:
                        $rotated = imagerotate($image, -90, 0);
                        if ($rotated !== false) {
                            imagedestroy($image);
                            $image = $rotated;
                        }
                        break;
                    case 8:
                        $rotated = imagerotate($image, 90, 0);
                        if ($rotated !== false) {
                            imagedestroy($image);
                            $image = $rotated;
                        }
                        break;
                }
            }
        } catch (\Throwable $e) {
            // Ignore exif errors and return original image
        }

        return $image;
    }
}
