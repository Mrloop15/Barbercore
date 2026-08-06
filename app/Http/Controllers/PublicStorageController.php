<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class PublicStorageController extends Controller
{
    public function __invoke(Request $request, string $path): StreamedResponse
    {
        $path = $this->decodePath($path);

        abort_unless($this->isSafeRelativePath($path), 404);

        $disk = Storage::disk('public');

        try {
            abort_unless($disk->exists($path), 404);

            $stream = $disk->readStream($path);
            abort_if($stream === false, 404);

            $mimeType = $disk->mimeType($path) ?: 'application/octet-stream';
            $size = $disk->size($path);
        } catch (Throwable) {
            abort(404);
        }

        return response()->stream(function () use ($stream): void {
            try {
                fpassthru($stream);
            } finally {
                fclose($stream);
            }
        }, 200, [
            'Content-Type' => $mimeType,
            'Content-Length' => (string) $size,
            'Cache-Control' => 'public, max-age=604800, immutable',
            'X-Content-Type-Options' => 'nosniff',
        ]);
    }

    private function decodePath(string $path): string
    {
        for ($attempt = 0; $attempt < 3; $attempt++) {
            $decoded = rawurldecode($path);

            if ($decoded === $path) {
                break;
            }

            $path = $decoded;
        }

        return $path;
    }

    private function isSafeRelativePath(string $path): bool
    {
        if ($path === '' || str_contains($path, "\0") || str_contains($path, '\\')) {
            return false;
        }

        if (str_starts_with($path, '/') || preg_match('/^[A-Za-z]:/', $path)) {
            return false;
        }

        foreach (explode('/', $path) as $segment) {
            if ($segment === '' || $segment === '.' || $segment === '..') {
                return false;
            }
        }

        return true;
    }
}
