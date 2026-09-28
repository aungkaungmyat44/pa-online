<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;
use RuntimeException;

class SubdistrictByOicSeeder extends Seeder
{
    public function run(): void
    {
        $path = public_path('excels/subdistricts_by_oic.xlsm');

        if (! is_file($path)) {
            throw new RuntimeException("Excel file not found: {$path}");
        }

        $spreadsheet = IOFactory::load($path);
        $sheet = $spreadsheet->getActiveSheet();

        $columns = [
            'A' => 'code',
            'B' => 'name',
            'C' => 'name_en',
            'D' => 'province_code',
            'E' => 'province_number',
            'F' => 'district_code',
            'G' => 'district_name',
            'H' => 'district_name_en',
            'I' => 'subdistrict_code',
            'J' => 'subdistrict_name',
            'K' => 'subdistrict_name_en',
        ];

        foreach ($columns as $column => $expectedHeader) {
            $actualHeader = trim(
                (string) $sheet->getCell("{$column}1")->getValue()
            );

            if ($actualHeader !== $expectedHeader) {
                throw new RuntimeException(
                    "Column {$column}: expected '{$expectedHeader}', found '{$actualHeader}'."
                );
            }
        }

        $value = static function (string $column, int $row) use ($sheet): ?string {
            // Formatted value preserves displayed codes such as "01" and "00".
            $text = trim(
                (string) $sheet->getCell("{$column}{$row}")->getFormattedValue()
            );

            return $text === '' ? null : $text;
        };

        $batch = [];
        $now = now()->toDateTimeString();

        for ($row = 2; $row <= $sheet->getHighestDataRow(); $row++) {
            $code = $value('A', $row);

            if ($code === null) {
                continue;
            }

            if (! preg_match('/^\d{6}$/', $code)) {
                throw new RuntimeException("Invalid code '{$code}' on Excel row {$row}.");
            }

            $batch[] = [
                'code' => $code,
                'name' => $value('B', $row),
                'name_en' => $value('C', $row),
                'province_code' => $value('D', $row),
                'province_number' => $value('E', $row),
                'district_code' => $value('F', $row),
                'district_name' => $value('G', $row),
                'district_name_en' => $value('H', $row),
                'subdistrict_code' => $value('I', $row),
                'subdistrict_name' => $value('J', $row),
                'subdistrict_name_en' => $value('K', $row),
                'created_at' => $now,
                'updated_at' => $now,
            ];

            if (count($batch) === 50) {
                $this->saveBatch($batch);
                $batch = [];
            }
        }

        if ($batch !== []) {
            $this->saveBatch($batch);
        }

        $spreadsheet->disconnectWorksheets();
    }

    private function saveBatch(array $batch): void
    {
        DB::table('subdistricts_by_oic')->upsert(
            $batch,
            ['code'],
            [
                'name',
                'name_en',
                'province_code',
                'province_number',
                'district_code',
                'district_name',
                'district_name_en',
                'subdistrict_code',
                'subdistrict_name',
                'subdistrict_name_en',
                'updated_at',
            ]
        );
    }
}