<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class PublicStorageTest extends TestCase
{
    public function test_it_serves_nested_public_files_with_cache_headers(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('servicios/example.png', $this->png());

        $response = $this->get('/storage/servicios/example.png');

        $response->assertOk()
            ->assertHeader('Content-Type', 'image/png')
            ->assertHeader('Cache-Control', 'immutable, max-age=604800, public')
            ->assertHeader('X-Content-Type-Options', 'nosniff');

        $this->assertSame($this->png(), $response->streamedContent());
    }

    public function test_it_returns_not_found_for_missing_public_files(): void
    {
        Storage::fake('public');

        $this->get('/storage/servicios/missing.jpg')->assertNotFound();
    }

    public function test_it_rejects_path_traversal(): void
    {
        Storage::fake('public');

        foreach ([
            '/storage/%2e%2e/.env',
            '/storage/%252e%252e/.env',
            '/storage/servicios/%2e%2e/.env',
            '/storage/C:%5C.env',
        ] as $url) {
            $this->get($url)->assertNotFound();
        }
    }

    private function png(): string
    {
        return base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR42mNk+A8AAQUBAScY42YAAAAASUVORK5CYII=');
    }
}
