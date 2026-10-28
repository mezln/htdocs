<?php

namespace Tests\Feature;

use App\Models\Game;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class GameRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_for_game_routes(): void
    {
        $this->get('/games')->assertRedirect('/login');
        $this->get('/games/create')->assertRedirect('/login');
    }

    public function test_customer_can_view_games_index_but_cannot_manage_games(): void
    {
        $customer = User::factory()->create();
        $customer->assignRole(Role::create(['name' => 'klant', 'guard_name' => 'web']));

        $game = Game::create([
            'game_name' => 'Test Game',
            'platform' => 'PC',
            'genre' => 'Action',
            'rating' => 8,
        ]);

        $this->actingAs($customer)->get('/games')->assertOk();
        $this->get("/games/show/{$game->id}")->assertForbidden();
        $this->get('/games/create')->assertForbidden();
        $this->post('/games/store')->assertForbidden();
        $this->get("/games/edit/{$game->id}")->assertForbidden();
        $this->post("/games/update/{$game->id}")->assertForbidden();
        $this->post("/games/destroy/{$game->id}")->assertForbidden();
    }

    public function test_admin_can_access_all_game_pages_and_actions(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(Role::create(['name' => 'admin', 'guard_name' => 'web']));

        $game = Game::create([
            'game_name' => 'Test Game',
            'platform' => 'PC',
            'genre' => 'Action',
            'rating' => 8,
        ]);

        $this->actingAs($admin);

        $this->get('/games')->assertOk();
        $this->get("/games/show/{$game->id}")->assertOk();
        $this->get('/games/create')->assertOk();
        $this->get("/games/edit/{$game->id}")->assertOk();
        $this->post('/games/store', [
            'game_name' => 'New Game',
            'platform' => 'PC',
            'genre' => 'Adventure',
            'rating' => 9,
        ])->assertRedirect('/games');
        $this->post("/games/update/{$game->id}", [
            'game_name' => 'Updated Game',
            'platform' => 'PC',
            'genre' => 'Action',
            'rating' => 7,
        ])->assertRedirect('/games');
        $this->post("/games/destroy/{$game->id}")->assertRedirect('/games');
    }
}
