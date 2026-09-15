<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function home()
    {
        return view('home', [
            'coveragePlans' => config('coverage_plans.plans', []),
            'coverageRows' => config('coverage_plans.rows', []),
        ]);
    }

    public function checkPremium()
    {
        $occupation1 = config('occupations.occupationForPlan1');
        $occupation2 = config('occupations.occupationForPlan2');
        $occupation3 = config('occupations.occupationForPlan3');
        $occupations = array_merge($occupation1, $occupation2, $occupation3);

        return view('check', [
            'occupations' => $occupations
        ]);
    }

    public function otpConfirmation(Request $request)
    {
        return view('otp', [
            'customer' => [
                'occupation' => $request->input('occupation'),
                'email' => $request->input('email'),
                'date_of_birth' => $request->input('date_of_birth'),
            ],
        ]);
    }

    public function healthQuestion(Request $request)
    {
        return view('health-questions', [
            'customer' => [
                'occupation' => $request->input('occupation'),
                'email' => $request->input('email'),
                'date_of_birth' => $request->input('date_of_birth'),
                'otp_code' => $request->input('otp_code'),
                'selected_plan' => config('coverage_plans.default_plan'),
            ],
        ]);
    }

    public function informationForm(Request $request)
    {
        $cardTypes = [
            'National ID Card',
            'Passport',
            'Alien ID Card',
            'Government / State Enterprise / Company / Partnership / Shop',
            'Other'
        ];

        return view('information-form', [
            'customer' => [
                'occupation' => $request->input('occupation'),
                'email' => $request->input('email'),
                'date_of_birth' => $request->input('date_of_birth'),
                'otp_code' => $request->input('otp_code'),
                'selected_plan' => $request->input('selected_plan'),
                'health_questions' => $request->input('health_questions', []),
            ],
            'cardTypes' => $cardTypes,
            'nameTitles' => $this->getNameTitles(),
            'countries' => $this->getCountries(),
            'provinces' => $this->getProvinces(),
        ]);
    }

    public function reviewInformation(Request $request)
    {
        return view('review-information', [
            'review' => $request->all(),
        ]);
    }

    public function paymentMethods(Request $request)
    {
        return view('payment-methods', [
            'payment' => $request->all(),
        ]);
    }

    public function paymentProcess(Request $request)
    {
        $payment = $request->all();
        $payment['payment_method'] = 'card';
        $payment['payment_variant'] = 'master';

        return view('payment-process', [
            'payment' => $payment,
        ]);
    }

    public function checkout(Request $request)
    {
        return view('receipt', [
            'policyNumber' => 'PA-0000001',
            'email' => $request->input('email'),
        ]);
    }

    public function checkPolicy(Request $request)
    {
        return view('check-policy', [
            'policyNumber' => $request->query('policy_number', 'PA-0000001'),
            'orderReference' => $request->query('ref', '1'),
        ]);
    }

    public function checkPolicyForm()
    {
        return view('check-policy-form');
    }

    // Json Helpers
    public function getNameTitles()
    {
        return DB::connection('helperDB')
            ->table('title')
            ->select('title', 'name_s')
            ->whereNotNull('title')
            ->whereNotNull('name_s')
            ->whereRaw("TRIM(title) != ''")
            ->whereRaw("TRIM(name_s) != ''")
            ->orderBy('title')
            ->get();
    }

    public function getCountries()
    {
        return DB::connection('helperDB')
            ->table('country')
            ->select('ct_code', 'ct_nameth', 'ct_nameeng')
            //->where('ct_code', '<>', 'THA')
            ->where('sts', '<>', '0')
            ->orderBy('position')
            ->get();
    }

    public function getProvinces()
    {
        return DB::connection('helperDB')
            ->table('ref_province')
            ->select([
                'Prov_code as province_code',
                'Prov_name as province_name',
                'Prov_num as province_number',
            ])
            ->where('Prov_num', '<>', 99)
            ->where('prov_code', '<>', '00')
            ->orderByRaw("oic_country = 'TH' DESC")
            ->orderBy('prov_code')
            ->get();
    }

    public function districts(Request $request)
    {
        $provinceCode = trim((string) $request->query('province_code', ''));

        $query = DB::connection('helperDB')
            ->table('ref_amphur')
            ->select([
                'amphurid as district_code',
                'amphurname as district_name',
            ])
            ->orderBy('amphurid');

        if ($provinceCode !== '') {
            $query->where('Prov_code', $provinceCode);
        }

        return response()->json([
            'data' => $query->get(),
        ]);
    }

    public function subdistricts(Request $request)
    {
        $provinceCode = trim((string) $request->query('province_code', ''));
        $districtCode = trim((string) $request->query('district_code', ''));

        if ($provinceCode === '' or $districtCode === '') {
            return response()->json([
                'data' => [],
                'message' => 'Province code and district code are required.',
            ], 400);
        }

        $subdistricts = DB::connection('helperDB')
            ->table('ref_tumbol')
            ->select([
                'tumbol_id as subdistrict_code',
                'tumbolname as subdistrict_name',
                'zipcode',
            ])
            ->where('Prov_code', $provinceCode)
            ->where('amphur_id', $districtCode)
            ->orderBy('tumbol_id')
            ->get();

        return response()->json([
            'data' => $subdistricts,
        ]);
    }
}
