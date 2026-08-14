<?php

namespace Tests\Feature;

use Tests\TestCase;

class DashboardClientCardsTest extends TestCase
{
    public function test_frequent_clients_use_an_accessible_five_item_card_track(): void
    {
        $view = file_get_contents(resource_path('views/dashboard/index.blade.php'));

        $this->assertStringContainsString('class="client-card-scroll"', $view);
        $this->assertStringContainsString('tabindex="0"', $view);
        $this->assertStringContainsString('role="region"', $view);
        $this->assertStringContainsString('$clientesFrecuentes->take(5)', $view);
        $this->assertStringContainsString('client-rank-avatar', $view);
        $this->assertStringContainsString("Storage::disk('public')->exists(\$cliente->foto)", $view);
        $this->assertStringContainsString("asset('storage/' . \$cliente->foto)", $view);
        $this->assertStringContainsString('width="48"', $view);
        $this->assertStringContainsString('height="48"', $view);
        $this->assertStringContainsString('loading="lazy"', $view);
    }

    public function test_client_card_scroll_is_isolated_from_the_page(): void
    {
        $styles = file_get_contents(public_path('css/barbercore-admin.css'));

        $this->assertMatchesRegularExpression(
            '/\.client-card-scroll\s*\{[^}]*width:\s*100%;[^}]*max-width:\s*100%;[^}]*overflow-x:\s*auto;[^}]*contain:\s*paint;[^}]*overscroll-behavior-x:\s*contain;/s',
            $styles,
        );
        $this->assertStringContainsString('touch-action: pan-x;', $styles);
        $this->assertMatchesRegularExpression(
            '/\.client-card-scroll::\-webkit-scrollbar\s*\{[^}]*display:\s*block;[^}]*height:\s*7px;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/\.client-card-scroll::\-webkit-scrollbar-track\s*\{[^}]*margin:\s*0 12px;[^}]*border-radius:\s*999px;[^}]*background:\s*rgba\(229,224,214,\.35\);/s',
            $styles,
        );
        $this->assertStringContainsString('.client-card-scroll::-webkit-scrollbar-thumb:hover { background: var(--dorado); }', $styles);
        $this->assertMatchesRegularExpression(
            '/\.client-card-track\s*\{[^}]*display:\s*flex;[^}]*width:\s*max-content;[^}]*min-width:\s*100%;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/\.client-rank-card\s*\{[^}]*flex:\s*0 0 132px;[^}]*scroll-snap-align:\s*start;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/\.client-rank-avatar\s*\{[^}]*flex:\s*0 0 48px;[^}]*width:\s*48px;[^}]*height:\s*48px;[^}]*min-width:\s*48px;[^}]*max-width:\s*48px;[^}]*overflow:\s*hidden;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/\.client-rank-avatar img\s*\{[^}]*position:\s*absolute;[^}]*inset:\s*0;[^}]*width:\s*100%;[^}]*height:\s*100%;[^}]*max-width:\s*100%;[^}]*max-height:\s*100%;[^}]*object-fit:\s*cover;[^}]*object-position:\s*center;/s',
            $styles,
        );
    }
}
