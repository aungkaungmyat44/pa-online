<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OtpFormRequest;
use App\Models\Plan;
use App\Models\Customer;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use App\Services\EmailService;

class PageController extends Controller
{
    public function home()
    {
        $plans = Plan::orderBy('id', 'asc')->get();

        $coverageRows = [
            [
                'label' => 'เสียชีวิตจากอุบัติเหตุ',
                'field' => 'death_coverage',
            ],
            [
                'label' => 'ถูกฆ่าหรือทำร้ายร่างกาย',
                'field' => 'assaulted_coverage',
            ],
            [
                'label' => 'ขับขี่/โดยสารรถจักรยานยนต์',
                'field' => 'vehicle_coverage',
            ],
            [
                'label' => 'ค่ารักษาพยาบาล',
                'field' => 'medical_expense_coverage',
            ],
        ];

        return view('home', [
            'coveragePlans' => $plans->map(fn ($plan) => [
                'title' => $plan->name_th,
                'subtitle' => $plan->name_en,
                'value' => $plan->name_th,
            ]),
            'coverageRows' => collect($coverageRows)->map(fn ($row) => [
                'label' => $row['label'],
                'amounts' => $plans->map(fn ($plan) => number_format((float) $plan->{$row['field']})),
            ]),
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

    public function otpConfirmation(OtpFormRequest $request)
    {
        $data = $request->validated();
        $email = trim($data['email']);
        $customer = Customer::where('email', $email)->first();

        if (empty($customer)) {
            $customer = Customer::create(['email' => $email]);
        }

        if (!$customer['is_otp_sent']) {
            $otp = $this->generateOtp(); // Example: "482193"
            $subject = 'PA Online - Sending OTP Code';

            $mailable = new OtpMail(
                data: [
                    'brandName' => 'PA Online',
                    'headerTitle' => 'Sending OTP Code',
                    'recipientName' => $customer->name ?? 'Customer',
                    'introText' => 'Please use the OTP code below to verify your account.',
                    'detailLabel' => 'OTP Code',
                    'detailValue' => (string) $otp,
                    'bodyMessage' => 'Do not share this code with anyone.',
                    'footerText' => 'Ignore this email if you did not request an OTP code.',
                    'autoReplyText' => 'Please do not reply to this email. This is an automated message.',
                ],
                subjectText: $subject,
            );

            $sent = app(EmailService::class)->sendEmailApi(
                $customer->email,
                $subject,
                $mailable->render(),
            );

            if ($sent) {
                $customer->update([
                    'otp_code' => $otp,
                    'is_otp_sent' => true,
                ]);
            } else {
                return redirect()->back()->withErrors(['email' => 'Failed to send OTP email. Please try again later.']);
            }
        }
        
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

    private function generateOtp(): string
    {
        return (string) random_int(100000, 999999);
    }
}
