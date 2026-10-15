<?php

namespace Tests\Feature;

use Tests\TestCase;

class LocaleNavigationTest extends TestCase
{
    public function test_english_locale_is_used_by_the_shared_navigation_and_routes(): void
    {
        $response = $this->withSession([
            'customer' => ['email' => 'customer@example.com'],
        ])->get('/en/otp-form');

        $response->assertOk()
            ->assertSee('<html lang="en">', false)
            ->assertSee('Language - EN')
            ->assertSee('Customer Service')
            ->assertSee('Agent/Broker')
            ->assertSee('About Us UPP')
            ->assertSee('/en/verify-otp"', false)
            ->assertSee('/th/otp-form"', false);
    }

    public function test_thai_locale_is_used_by_the_shared_navigation_and_routes(): void
    {
        $response = $this->withSession([
            'customer' => ['email' => 'customer@example.com'],
        ])->get('/th/otp-form');

        $response->assertOk()
            ->assertSee('<html lang="th">', false)
            ->assertSee('Language - TH')
            ->assertSee('บริการลูกค้า')
            ->assertSee('เกี่ยวกับเรา UPP')
            ->assertSee('/th/verify-otp"', false)
            ->assertSee('/en/otp-form"', false);
    }

    public function test_unsupported_locale_prefixes_are_not_routed(): void
    {
        $this->get('/fr/otp-form')->assertNotFound();
    }
}
