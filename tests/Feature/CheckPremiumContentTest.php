<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class CheckPremiumContentTest extends TestCase
{
    public function test_check_premium_form_and_underwriting_terms_are_localized(): void
    {
        foreach ([
            'th' => [
                'heading' => 'กรุณากรอกข้อมูลต่อไปนี้เพื่อดำเนินการต่อ',
                'occupation' => 'อาชีพ',
                'option' => 'พนักงานสำนักงาน',
                'terms' => 'เงื่อนไขการรับประกัน',
                'exclusion' => 'ข้อยกเว้นการรับประกัน',
                'excluded_occupations' => 'รายละเอียดอาชีพที่ไม่คุ้มครอง',
                'continue' => 'ดำเนินการต่อ',
                'progress' => 'สอบถามข้อมูล',
            ],
            'en' => [
                'heading' => 'Please enter the following information to continue',
                'occupation' => 'Occupation',
                'option' => 'Office worker',
                'terms' => 'Underwriting Conditions',
                'exclusion' => 'Policy Exclusions',
                'excluded_occupations' => 'Occupations Not Covered',
                'continue' => 'Continue',
                'progress' => 'Personal Details',
            ],
        ] as $locale => $expected) {
            app()->setLocale($locale);
            URL::defaults(['locale' => $locale]);

            $html = view('check', [
                'occupations' => ['office-worker' => $expected['option']],
                'errors' => new ViewErrorBag,
            ])->render();

            foreach ($expected as $text) {
                $this->assertStringContainsString($text, $html);
            }
        }
    }
}
