<?php

namespace Tests\Feature\Boardgame;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Boardgame;
use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteBoardgameTest extends ApiTestCase
{
    use RefreshDatabase;
    private array $userLoginHeaders;

    protected function setUp(): void
    {
    parent::setUp();
    $this->userLoginHeaders = [
        'Authorization' => 'Bearer' . $this->loginAsUser()
    ];
    }
    protected function shouldSeed()
    {
        return true;
    }
    /**
     * List Boardgames.
     *
     * @return void
     */
    public function test_boardgames_can_be_delete()
    {
        $user = User::where('email', 'test@example.com')->firstOrFail();
        $boardgame = Boardgame::factory()->create(
            [
                "user_id"=>$user->id,
            ]
        );
        
        $response = $this->delete('/api/boardgame/item/delete/'.$boardgame->id,  $this->userLoginHeaders);
        
        $response->assertStatus(200);
        
        $parsedResponse = json_decode($response->content());
        
        $this->assertFalse(Boardgame::where('id', $boardgame->id)->exists());        
    }
}
