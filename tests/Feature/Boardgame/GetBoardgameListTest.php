<?php

namespace Tests\Feature\Boardgame;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Boardgame;
use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetBoardgameListTest extends ApiTestCase
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
    public function test_boardgames_can_be_listed()
    {
        $user = User::where('email', 'test@example.com')->firstOrFail();
        $boardgame = Boardgame::factory(3)->create(
            [
                "user_id"=>$user->id,
            ]
        );
        $response = $this->get('/api/boardgame/items/'.$user->id,  $this->userLoginHeaders);

        $response->assertStatus(200);
        $this->assertCount(3,$user->boardgames()->get());
        
        $parsedResponse = json_decode($response->content());
        $this->assertObjectHasAttribute('id', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('label', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('description', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('editorial', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('min_players', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('max_players', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('user_id', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('created_at', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('updated_at', $parsedResponse->data[0]);
    }
}
