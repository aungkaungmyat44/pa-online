<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        $plans = [
            [
                'name_en' => 'Class 1',
                'name_th' => 'อาชีพชั้น 1',
                'death_coverage' => 300000,
                'assaulted_coverage' => 300000,
                'vehicle_coverage' => 150000,
                'medical_expense_coverage' => 30000,
                'premium_amount' => null,
            ],
            [
                'name_en' => 'Class 2',
                'name_th' => 'อาชีพชั้น 2',
                'death_coverage' => 200000,
                'assaulted_coverage' => 200000,
                'vehicle_coverage' => 100000,
                'medical_expense_coverage' => 30000,
                'premium_amount' => null,
            ],
            [
                'name_en' => 'Class 3',
                'name_th' => 'อาชีพชั้น 3',
                'death_coverage' => 100000,
                'assaulted_coverage' => 100000,
                'vehicle_coverage' => 50000,
                'medical_expense_coverage' => 10000,
                'premium_amount' => null,
            ],
        ];

        foreach ($plans as $plan) {
            Plan::updateOrCreate(
                ['name_th' => $plan['name_th']],
                $plan
            );
        }
    }
}