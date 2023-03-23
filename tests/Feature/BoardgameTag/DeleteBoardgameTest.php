<?php

namespace Tests\Feature\BoardgameTag;

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
    public function test_boardgame_tag_relationships_can_be_deleted()
    {
        $user = User::where('email', 'test@example.com')->firstOrFail();
        $boardgame = Boardgame::factory()->create(
            [
                "user_id"=>$user->id,
            ]
        );
        $boardgame->tags()->attach(1);
        $boardgame->tags()->attach(2);
        $boardgame->tags()->attach(3);

        $this->assertTrue($boardgame->tags()->where('tags.id', 1)->exists());
        $this->assertTrue($boardgame->tags()->where('tags.id', 2)->exists());
        $this->assertTrue($boardgame->tags()->where('tags.id', 3)->exists());
        
        $response = $this->delete('/api/boardgame/item/delete/'.$boardgame->id,  $this->userLoginHeaders);
        
        $response->assertStatus(200);
        
        //Check fields of Boardgames
        $parsedResponse = json_decode($response->content());
        
        $this->assertFalse(Boardgame::where('id', $boardgame->id)->exists());
        
                $this->assertTrue($boardgame->tags()->where('tags.id', 1)->doesntExist());
                $this->assertTrue($boardgame->tags()->where('tags.id', 2)->doesntExist());
                $this->assertTrue($boardgame->tags()->where('tags.id', 3)->doesntExist());
        
    }
}
