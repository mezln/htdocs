<?php

namespace Tests\Feature;

use App\Models\Game;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GameRoutesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_view_games_index(): void
    {
        $this->get('/games')->assertOk();
    }

    public function test_guest_is_redirected_to_login_for_game_management_routes(): void
    {
        $game = Game::create([
            'game_name' => 'Test Game',
            'platform' => 'PC',
            'genre' => 'Action',
            'rating' => 8,
        ]);

        $this->get('/games/create')->assertRedirect('/login');
        $this->post('/games/store')->assertRedirect('/login');
        $this->get("/games/edit/{$game->id}")->assertRedirect('/login');
        $this->post("/games/update/{$game->id}")->assertRedirect('/login');
        $this->post("/games/destroy/{$game->id}")->assertRedirect('/login');
    }
}
