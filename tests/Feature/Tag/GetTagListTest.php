<?php

namespace Tests\Feature\Tag;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Models\User;
use App\Models\Tag;
use Tests\ApiTestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class GetTagListTest extends ApiTestCase
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
        $test_tag = $this->generateTag();
        $response = $this->get('/api/tag/items', $this->userLoginHeaders);

        $response->assertStatus(200);

        //Check fields of Tags
        $parsedResponse = json_decode($response->content());

        $this->assertObjectHasAttribute('id', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('label', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('description', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('created_at', $parsedResponse->data[0]);
        $this->assertObjectHasAttribute('updated_at', $parsedResponse->data[0]);

        //check one of their
        $this->assertTrue(Tag::where('id', $test_tag->id)->exists());
        $recovered_tag = Tag::findOrFail($test_tag->id);
        $this->assertEquals($test_tag->id, $recovered_tag->id);
        $this->assertEquals($test_tag->label, $recovered_tag->label);
        $this->assertEquals($test_tag->description, $recovered_tag->description);
        $this->assertEquals($test_tag->created_at, $recovered_tag->created_at);
        $this->assertEquals($test_tag->updated_at, $recovered_tag->updated_at);
    }

    public function generateTag(){
        $tag = Tag::factory()->create();
        return $tag;
    }
}
