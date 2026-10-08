<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SecretPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_secret_page(): void
    {
        $this->get('/geheim')->assertRedirect('/login');
    }

    public function test_authenticated_user_can_view_secret_page(): void
    {
        $this->actingAs(User::factory()->create())
            ->get('/geheim')
            ->assertOk()
            ->assertSee('Geheime pagina');
    }
}
