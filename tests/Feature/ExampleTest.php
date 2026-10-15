<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_the_root_redirects_to_the_default_language(): void
    {
        $locale = in_array(config('app.locale'), ['th', 'en'], true)
            ? config('app.locale')
            : 'th';

        $this->get('/')
            ->assertRedirect(route('home', ['locale' => $locale]));
    }
}
