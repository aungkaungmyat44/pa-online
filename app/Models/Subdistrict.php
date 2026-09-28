<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subdistrict extends Model
{
    protected $table = 'subdistricts_by_oic';

    protected $fillable = [
        'code',
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
    ];
}