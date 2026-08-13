<?php

namespace Tests\Feature;

use Tests\TestCase;

class LogoutConfirmationTest extends TestCase
{
    public function test_logout_requires_the_shared_confirmation_dialog(): void
    {
        $layout = file_get_contents(resource_path('views/layouts/app.blade.php'));
        $dialog = file_get_contents(public_path('js/barber-dialog.js'));

        $this->assertMatchesRegularExpression(
            '/<form\s+method="POST"\s+action="\{\{ route\(\'logout\'\) \}\}"\s+id="logoutForm"\s+data-confirm-title="¿Cerrar sesión\?"\s+data-confirm="Tu sesión se cerrará en este dispositivo\."\s+data-confirm-text="Cerrar sesión"\s+data-confirm-cancel-text="Continuar aquí"/s',
            $layout,
        );
        $this->assertStringContainsString("form.dataset.confirmed = 'true';", $dialog);
        $this->assertStringContainsString('form.dataset.confirmCancelText || form.dataset.cancelText', $dialog);
        $this->assertStringContainsString('form.requestSubmit(submitter || undefined)', $dialog);
    }
}
