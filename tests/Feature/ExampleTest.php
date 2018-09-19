<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{
    public $post;
    public function setUp()
    {
        parent::setUp();
        $this->post = factory('App\Models\Post')->create();

    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $response = $this->get('/');

        $response->assertStatus(200);
    }

    public function testPostsAreCreatedAndShowedAtList()
    {
        $this->get('/posts')->assertSee($this->post->body);
    }

    public function testPostsAreCreatedAndShowedAtEditPage()
    {
        $this->get('/posts/'.$this->post->id.'/edit')->assertSee($this->post->happened_at);
    }
}
