<?php

namespace Tests\Feature;

use App\Models\Star;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StarTest extends TestCase
{
    use RefreshDatabase;

    // Test CRUD of stars

    public function testCreateStars()
    {
        $stars = Star::factory()->count(5)->create();
        $this->assertNotNull($stars);
        $this->assertNotEmpty($stars);

        $this->assertDatabaseCount(Star::class, 5);
    }

    public function testUpdateStars()
    {
        $firstName = 'Millord';
        $lastName = 'Drollim';
        $desc = 'A laravel developer';

        Star::factory()->create([
            'first_name' => $firstName,
            'last_name' => $lastName,
            'img_path' => 'no_img',
            'description' => $desc
        ]);
        $this->assertDatabaseCount(Star::class, 1);

        // get last star from inmemory db
        /** @var Star $star */
        $star = Star::all()->last();
        $this->assertDatabaseHas(Star::class, [
            'first_name' => $firstName,
            'last_name' => $lastName,
            'description' => $desc
        ]);

        // update star with a new description
        $newDesc = 'A candidate';
        $star->update([ 'description' => $newDesc ]);

        // verify the previous 'description' is not existing anymore
        $this->assertDatabaseMissing(Star::class, [ 'description' => $desc ]);
        // verify the new one is saved
        $this->assertDatabaseHas(Star::class, [ 'description' => $newDesc ]);
    }

    public function testDeleteStar()
    {
        Star::factory()->count(5)->create();
        $this->assertDatabaseCount(Star::class, 5);

        /** @var Star $star */
        $star = Star::all()->last();
        $star->delete();

        $this->assertDatabaseMissing(Star::class, [
            'first_name' => $star->getFirstName(),
            'last_name' => $star->getLastName(),
            'description' => $star->getDescription()
        ]);
    }

}
