<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_root_redirects_to_thai_even_when_the_app_locale_is_english(): void
    {
        config(['app.locale' => 'en']);

        $this->get('/')
            ->assertRedirect(route('home', ['locale' => 'th']));
    }
}
