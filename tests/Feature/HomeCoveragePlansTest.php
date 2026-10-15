<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\ViewErrorBag;
use Tests\TestCase;

class HomeCoveragePlansTest extends TestCase
{
    public function test_home_coverage_table_renders_localized_labels_and_plan_names(): void
    {
        $coverageFields = config('coverage_plans.coverage_fields');

        foreach ([
            'th' => [
                'heading' => 'แผนความคุ้มครอง',
                'coverage' => 'ความคุ้มครอง',
                'plan' => 'อาชีพชั้น 1',
                'label' => 'เสียชีวิตจากอุบัติเหตุ',
                'intro' => 'ประกันภัยอุบัติเหตุส่วนบุคคลคืออะไร ?',
                'highlight' => 'คุ้มครองครอบคลุมสำหรับทั้งครอบครัว',
                'faq' => 'คำถามที่พบบ่อย',
            ],
            'en' => [
                'heading' => 'Coverage Plans',
                'coverage' => 'Coverage',
                'plan' => 'Class 1',
                'label' => 'Accidental Death',
                'intro' => 'What is Personal Accident Insurance?',
                'highlight' => 'Comprehensive protection for the whole family',
                'faq' => 'Frequently Asked Questions',
            ],
        ] as $locale => $expected) {
            app()->setLocale($locale);
            URL::defaults(['locale' => $locale]);

            $coverageRows = collect($coverageFields)->map(fn ($field) => [
                'label' => __("content.coverage_fields.{$field['field']}"),
                'amounts' => collect(['300,000']),
            ]);

            $html = view('home', [
                'coveragePlans' => collect([[
                    'title' => $expected['plan'],
                ]]),
                'coverageRows' => $coverageRows,
                'errors' => new ViewErrorBag,
            ])->render();

            $this->assertStringContainsString($expected['heading'], $html);
            $this->assertStringContainsString($expected['coverage'], $html);
            $this->assertStringContainsString($expected['plan'], $html);
            $this->assertStringContainsString($expected['label'], $html);
            $this->assertStringContainsString($expected['intro'], $html);
            $this->assertStringContainsString($expected['highlight'], $html);
            $this->assertStringContainsString($expected['faq'], $html);
            $this->assertStringContainsString('300,000', $html);
        }
    }
}
