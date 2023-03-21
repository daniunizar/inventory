<?php

namespace Tests\Feature\Boardgame;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Boardgame;
use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;

class StoreBoardgameTest extends ApiTestCase
{
    use RefreshDatabase;
    private array $userLoginHeaders;
    private $payloadData;
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
    public function test_boardgames_can_be_stored()
    {
        $this->payloadData = $this->getPayloadData();
        $response = $this->post('/api/boardgame/item/store', $this->payloadData,  $this->userLoginHeaders);
        
        //Check response
        $response->assertStatus(201);
        
        $parsedResponse = json_decode($response->content());
        // dd($parsedResponse);
        //Check new boardgame values   
        $this->assertTrue(Boardgame::where('id', $parsedResponse->data->id)->exists());
        $this->assertEquals($this->payloadData['label'], $parsedResponse->data->label);
        $this->assertEquals($this->payloadData['description'], $parsedResponse->data->description);
        $this->assertEquals($this->payloadData['editorial'], $parsedResponse->data->editorial);
        $this->assertEquals($this->payloadData['min_players'], $parsedResponse->data->min_players);
        $this->assertEquals($this->payloadData['max_players'], $parsedResponse->data->max_players);
        $this->assertEquals($this->payloadData['min_age'], $parsedResponse->data->min_age);
        $this->assertEquals($this->payloadData['max_age'], $parsedResponse->data->max_age);
        $this->assertEquals($this->payloadData['user_id'], $parsedResponse->data->user_id);
    }

    public function getPayloadData() :array{
        $payloadData = Boardgame::factory()->make([
            'user_id'=>Auth::id(),
        ]);
        return $payloadData->getAttributes();
    }
}