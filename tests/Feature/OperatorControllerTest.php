<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OperatorControllerTest extends TestCase
{
    use RefreshDatabase; // Сбрасывает БД после каждого теста

    /**
     * Test operator dashboard access for authenticated user.
     */
    public function test_operator_dashboard_access()
    {
        $user = User::factory()->create(['is_admin' => false]);

        $response = $this->actingAs($user)
            ->get('/operator');

        $response->assertOk();
    }

    /**
     * Test redirect for unauthenticated users.
     */
    public function test_guest_redirect_to_login(): void
    {
        $this->get('/operator')
            ->assertRedirect('/login'); // Должен перенаправить
    }

    /**
     * Test admin cannot access operator panel (если нужно разделение прав).
     */
    public function test_admin_cannot_access_operator_panel()
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)
            ->get('/operator');

        // Ожидаем либо редирект, либо 403
        $response->assertStatus(403); // Или assertRedirect('/admin')
    }
}
