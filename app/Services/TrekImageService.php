<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class TrekImageService
{
    /**
     * Guarda una imagen de trek en /public/images/treks y devuelve su URL pública
     */
    public function storeUploadedImage(UploadedFile $file, ?string $previousUrl = null): string
    {
        // Directorio público desde donde el frontend sirve las imágenes
        $directory = public_path('images/treks');
        File::ensureDirectoryExists($directory);

        $extension = $file->getClientOriginalExtension() ?: 'jpg';
        // Nombre único para evitar colisiones entre cargas
        $filename = Str::uuid()->toString() . '.' . $extension;

        // Solo permitimos borrar archivos dentro de /images/treks por seguridad
        if ($previousUrl && str_starts_with($previousUrl, '/images/treks/')) {
            $previousPath = public_path(ltrim($previousUrl, '/'));
            if (File::exists($previousPath)) {
                File::delete($previousPath);
            }
        }

        $file->move($directory, $filename);

        // Se devuelve una ruta pública relativa para guardar en BD
        return '/images/treks/' . $filename;
    }
}
