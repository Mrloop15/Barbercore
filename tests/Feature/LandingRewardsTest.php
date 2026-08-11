<?php

namespace Tests\Feature;

use Tests\TestCase;

class LandingRewardsTest extends TestCase
{
    public function test_rewards_summary_uses_helpful_copy_and_available_count(): void
    {
        $landing = file_get_contents(resource_path('views/landing.blade.php'));

        $this->assertStringNotContainsString('Disponibilidad protegida', $landing);
        $this->assertStringNotContainsString('Cliente verificado', $landing);
        $this->assertStringContainsString('Beneficios para ti', $landing);
        $this->assertStringContainsString('const availableRewards = data.recompensas.filter', $landing);
        $this->assertStringContainsString("availableRewards === 1 ? 'disponible' : 'disponibles'", $landing);
    }

    public function test_rewards_content_is_contained_on_mobile(): void
    {
        $styles = file_get_contents(public_path('css/barbercore-landing.css'));

        $this->assertMatchesRegularExpression(
            '/\.booking-dialog\s*\{[^}]*max-width:\s*100%;[^}]*overflow-x:\s*hidden;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/\.rewards-client\s*\{[^}]*flex-wrap:\s*wrap;[^}]*min-width:\s*0;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/\.reward-item-copy\s*\{[^}]*min-width:\s*0;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/\.reward-item-points\s*\{[^}]*max-width:\s*100%;[^}]*white-space:\s*normal;[^}]*overflow-wrap:\s*anywhere;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/\.rewards-empty,\s*\.rewards-error\s*\{[^}]*overflow-wrap:\s*anywhere;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*500px\)\s*\{.*\.booking-field input,\s*\.booking-field select,\s*\.booking-field textarea\s*\{\s*font-size:\s*16px;/s',
            $styles,
        );
        $this->assertMatchesRegularExpression(
            '/@media\s*\(max-width:\s*500px\)\s*\{.*\.header-inner\s*\{[^}]*grid-template-columns:\s*minmax\(0,\s*1fr\)\s*auto;[^}]*gap:\s*12px;/s',
            $styles,
        );
    }

    public function test_reward_values_continue_to_be_escaped_before_rendering(): void
    {
        $landing = file_get_contents(resource_path('views/landing.blade.php'));

        $this->assertStringContainsString('escapeHtml(recompensa.nombre)', $landing);
        $this->assertStringContainsString('escapeHtml(recompensa.descripcion)', $landing);
        $this->assertStringContainsString('escapeHtml(recompensa.puntos_requeridos)', $landing);
    }
}
