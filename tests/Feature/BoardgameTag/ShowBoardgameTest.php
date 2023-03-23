<?php

namespace Tests\Feature\BoardgameTag;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Boardgame;
use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ShowBoardgameTest extends ApiTestCase
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
    public function test_boardgames_can_be_shown_with_tags()
    {
        $user = User::where('email', 'test@example.com')->firstOrFail();
        $tag_ids = [1,2,3];
        $boardgame = Boardgame::factory()->withTags($tag_ids)->create(
            [
                "user_id"=>$user->id,
            ]
        );

        $response = $this->get('/api/boardgame/item/show/'.$boardgame->id,  $this->userLoginHeaders);

        $response->assertStatus(200);
        
        //Check fields of Boardgames
        $parsedResponse = json_decode($response->content());

        $this->assertObjectHasAttribute('id', $parsedResponse->data);
        $this->assertObjectHasAttribute('label', $parsedResponse->data);
        $this->assertObjectHasAttribute('description', $parsedResponse->data);
        $this->assertObjectHasAttribute('editorial', $parsedResponse->data);
        $this->assertObjectHasAttribute('min_players', $parsedResponse->data);
        $this->assertObjectHasAttribute('max_players', $parsedResponse->data);
        $this->assertObjectHasAttribute('min_age', $parsedResponse->data);
        $this->assertObjectHasAttribute('max_age', $parsedResponse->data);
        $this->assertObjectHasAttribute('user_id', $parsedResponse->data);
        $this->assertObjectHasAttribute('created_at', $parsedResponse->data);
        $this->assertObjectHasAttribute('updated_at', $parsedResponse->data);
        $this->assertObjectHasAttribute('tags', $parsedResponse->data);

        //Check values of Boardgames
        $this->assertEquals($boardgame->id, $parsedResponse->data->id);
        $this->assertEquals($boardgame->label, $parsedResponse->data->label);
        $this->assertEquals($boardgame->description, $parsedResponse->data->description);
        $this->assertEquals($boardgame->editorial, $parsedResponse->data->editorial);
        $this->assertEquals($boardgame->min_players, $parsedResponse->data->min_players);
        $this->assertEquals($boardgame->max_players, $parsedResponse->data->max_players);
        $this->assertEquals($boardgame->min_age, $parsedResponse->data->min_age);
        $this->assertEquals($boardgame->max_age, $parsedResponse->data->max_age);
        $this->assertEquals($boardgame->user_id, $parsedResponse->data->user_id);
        //check tags
        $this->assertTrue($boardgame->tags()->where('tags.id', 1)->exists());
        $this->assertTrue($boardgame->tags()->where('tags.id', 2)->exists());
        $this->assertTrue($boardgame->tags()->where('tags.id', 3)->exists());
        // todo parse Dates? 
        // $this->assertEquals($boardgame->created_at, $parsedResponse->data[$key]->created_at);
        // $this->assertEquals($boardgame->updated_at, $parsedResponse->data[$key]->updated_at); 
    }
}
