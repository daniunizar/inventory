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
        $boardgames = Boardgame::factory(3)->create(
            [
                "user_id"=>$user->id,
            ]
        );

        $response = $this->get('/api/boardgame/items/'.$user->id,  $this->userLoginHeaders);

        $response->assertStatus(200);

        //Check number of boardgames
        $this->assertCount(3,$user->boardgames()->get());
        
        //Check fields of Boardgames
        $parsedResponse = json_decode($response->content());

        $this->assertObjectHasAttribute('id', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('label', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('description', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('editorial', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('min_players', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('max_players', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('min_age', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('max_age', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('user_id', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('created_at', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('updated_at', $parsedResponse->data[0]);

        //Check values of Boardgames
        foreach($boardgames as $key => $boardgame){
            $this->assertEquals($boardgame->id, $parsedResponse->data[$key]->id);
            $this->assertEquals($boardgame->label, $parsedResponse->data[$key]->label);
            $this->assertEquals($boardgame->description, $parsedResponse->data[$key]->description);
            $this->assertEquals($boardgame->editorial, $parsedResponse->data[$key]->editorial);
            $this->assertEquals($boardgame->min_players, $parsedResponse->data[$key]->min_players);
            $this->assertEquals($boardgame->max_players, $parsedResponse->data[$key]->max_players);
            $this->assertEquals($boardgame->min_age, $parsedResponse->data[$key]->min_age);
            $this->assertEquals($boardgame->max_age, $parsedResponse->data[$key]->max_age);
            $this->assertEquals($boardgame->user_id, $parsedResponse->data[$key]->user_id);
            // todo parse Dates? 
            // $this->assertEquals($boardgame->created_at, $parsedResponse->data[$key]->created_at);
            // $this->assertEquals($boardgame->updated_at, $parsedResponse->data[$key]->updated_at);
        }
    }
}
