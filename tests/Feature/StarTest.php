<?php

namespace Tests\Feature;

use App\Models\Star;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StarTest extends TestCase
{
    use RefreshDatabase;

    public function testCreateStars()
    {
        $stars = Star::factory()->count(5)->create();
        $this->assertNotNull($stars);
        $this->assertNotEmpty($stars);

        $this->assertDatabaseCount(Star::class, 5);
    }



}
