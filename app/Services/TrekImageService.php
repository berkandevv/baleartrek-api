<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TrekImageService
{
    public function storeUploadedImage(UploadedFile $file, ?string $previousUrl = null): string
    {
        $directory = public_path('images/treks');
        File::ensureDirectoryExists($directory);

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        $filename = Str::uuid()->toString() . '.' . $extension;

        if ($previousUrl && str_starts_with($previousUrl, '/images/treks/')) {
            $previousPath = public_path(ltrim($previousUrl, '/'));
            if (File::exists($previousPath)) {
                File::delete($previousPath);
            }
        }

        $file->move($directory, $filename);

        return '/images/treks/' . $filename;
    }
}
