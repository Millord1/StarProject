<?php

namespace Tests\Feature;

use App\Models\Star;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class StarIntegrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     *
     * @return void
     */
    public function testStarIndex()
    {
        $stars = Star::factory()->count(5)->create()->toArray();

        $response = $this->get('/api/stars');
        $response->assertStatus(200)
            ->assertJson($stars, false)
            ->assertJsonCount(5);
    }

    public function testStarValidator()
    {
        $wrongStar = [
            'first_name'    => 'Millord',
            'last_name'     => 'Drollim',
            'img_path'      => 'no_img',
        ];

        // Try to push an array of data with a missing 'required' field (description)
        $response = $this->json('POST', '/api/stars', $wrongStar)
            ->assertStatus(400);
        $this->assertEquals("The description field is required.", $response->content());

        // Try to create a star with bad data (string longer than expected by validator)
        $wrongStar['description'] = 'A backend dev';
        $wrongStar['first_name'] = Str::random(51);
        $response = $this->json('POST', '/api/stars', $wrongStar)
            ->assertStatus(400);
        $this->assertEquals("The first name must not be greater than 50 characters.", $response->content());

        // Change the wrong data to have an int value instead of a string value
        $wrongStar['first_name'] = 'Millord';
        $wrongStar['img_path'] = 123456;
        $response = $this->json('POST', '/api/stars', $wrongStar)
            ->assertStatus(400);
        $this->assertEquals("The img path must be a string.", $response->content());

        // Try to push a special char
        $wrongStar['img_path'] = 'no_img';
        $wrongStar['last_name'] = '/Weird~name';
        $response = $this->json('POST', '/api/stars', $wrongStar)
            ->assertStatus(400);
        $this->assertEquals("The last name must only contain letters, numbers, dashes and underscores.", $response->content());
    }

    public function testStarStore()
    {
        $newStar = [
            'first_name'    => 'Millord',
            'last_name'     => 'Drollim',
            'img_path'      => 'no_img',
            'description'   => 'A backend dev'
        ];

        // count stars before creating a new one
        $nbStarsBeforeStore = Star::all()->count();

        // create a star
        $response = $this->json('POST', '/api/stars', $newStar);
        $totalStars = Star::all()->count();

        $response->assertStatus(200)
            ->assertJson($newStar);

        // verify that the good nb of stars appears
        $indexResponse = $this->get('/api/stars');
        $indexResponse->assertJsonCount($totalStars);

        // verify we don't have the same amount of stars before and after creation
        $this->assertNotEquals($nbStarsBeforeStore, $totalStars);

        // Test that we can't create a star with the same first and last name than existing one
        $badStar = [
            'first_name'    => 'Millord',
            'last_name'     => 'Drollim',
            'img_path'      => 'still_no_img',
            'description'   => 'a creation that should be impossible'
        ];
        $this->json('POST', '/api/stars', $badStar)
            ->assertStatus(500);
    }

    public function testStarShow()
    {
        Star::factory()->count(3)->create();
        $starId = rand(1, 3);

        /** @var Star $starData */
        $starData = Star::all()->where('id', '=', $starId)->first();

        $this->get("/api/stars/$starId")
            ->assertStatus(200)
            ->assertJson($starData->attributesToArray());
    }

    public function testStarUpdate()
    {
        Star::factory()->count(1)->create();
        /** @var Star $star */
        $star = Star::all()->first();
        $starData = $star->attributesToArray();

        // modify data of the one created by the factory
        $newStarData = $starData;
        $newStarData['img_path'] = "There is no img for this star, it's an invisible one";
        $newStarData['description'] = 'Nobody knows what this star looks like';

        // push the update
        $response = $this->put("/api/stars/$star->id", $newStarData);
        $response->assertStatus(200)
            ->assertJson($newStarData);
    }

    public function testStarDestruction()
    {
        Star::factory()->count(5)->create();
        /** @var Star $star */
        $starId = Star::all()->first()->id;

        // total before deleting
        $totalStars = Star::all()->count();
        $this->assertEquals(5, $totalStars);

        $this->delete("api/stars/$starId")
            ->assertStatus(200);

        // verify there is less star now
        $newTotalStars = Star::all()->count();
        $this->assertGreaterThan($newTotalStars, $totalStars);

        // try to delete with a non-existing id
        $impossibleId = rand(100, 1000);
        $response = $this->delete("api/stars/$impossibleId")
            ->assertStatus(400);
        $this->assertEquals("Star not found from id $impossibleId", $response->content());
    }
}
