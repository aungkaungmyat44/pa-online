<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\OtpFormRequest;
use App\Http\Requests\HealthQuestionRequest;
use App\Http\Requests\SaveInformationRequest;
use App\Models\Plan;
use App\Models\Customer;
use App\Models\PlanOccupation;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use App\Services\EmailService;

class PageController extends Controller
{
    public function home()
    {
        $plans = Plan::orderBy('id', 'asc')->get();
        $coverageFields = config('coverage_plans.coverage_fields', []);

        return view('home', [
            'coveragePlans' => $plans->map(fn ($plan) => [
                'title'    => $plan->name_th,
                'subtitle' => $plan->name_en,
                'value'    => $plan->name_th,
            ]),
            'coverageRows' => collect($coverageFields)->map(fn ($row) => [
                'label' => $row['label'],
                'amounts' => $plans->map(
                    fn ($plan) => number_format((float) $plan->{$row['field']})
                ),
            ]),
        ]);
    }

    public function checkPremium()
    {
        $occupations = PlanOccupation::orderBy('id', 'asc')
                                    ->pluck('occupation_th', 'occupation_slug')
                                    ->toArray();

        return view('check', [
            'occupations' => $occupations,
        ]);
    }

    public function otpConfirmation(OtpFormRequest $request)
    {
        $data = $request->validated();
        $email = trim($data['email']);

        $customer = Customer::firstOrCreate([
            'email' => $email,
        ]);

        if (
            !$customer->is_otp_sent or
            blank($customer->otp_code) or
            !$customer->otp_expires_at or
            now()->greaterThanOrEqualTo($customer->otp_expires_at)
        ) {
            $otp = $this->generateOtp();
            $subject = 'PA Online - Sending OTP Code';

            $mailable = new OtpMail(
                data: [
                    'brandName' => 'PA Online',
                    'headerTitle' => 'Sending OTP Code',
                    'recipientName' => $customer->name ?? 'Customer',
                    'introText' => 'Please use the OTP code below to verify your account.',
                    'detailLabel' => 'OTP Code',
                    'detailValue' => $otp,
                    'bodyMessage' => 'This code expires in 2 minutes. Do not share this code with anyone.',
                    'footerText' => 'Ignore this email if you did not request an OTP code.',
                    'autoReplyText' => 'Please do not reply to this email. This is an automated message.',
                ],
                subjectText: $subject,
            );

            $sent = app(EmailService::class)->sendEmailApi($customer->email, $subject, $mailable->render(),);

            if (!$sent) {
                return $this->redirectRoute(
                    'check-premium',
                    errors: [
                        'email' => 'Failed to send OTP email. Please try again later.',
                    ],
                    input: $request->only('occupation', 'email', 'date_of_birth'),
                );
            }

            $customer->update([
                'otp_code' => $otp,
                'is_otp_sent' => true,
                'otp_expires_at' => now()->addMinutes(2),
                'otp_attempts' => 1,
            ]);
        }

        $request->session()->put('customer', [
            'occupation'    => $data['occupation'],
            'email'         => $email,
            'date_of_birth' => $data['date_of_birth'],
        ]);

        return $this->redirectRoute('otp-form');
    }

    public function showOtpForm(Request $request)
    {
        $customer = $request->session()->get('customer');

        if (empty($customer['email'])) {
            return $this->redirectRoute('check-premium', errors: [
                'email' => 'Please request an OTP first.',
            ]);
        }

        return view('otp', [
            'customer' => $customer,
        ]);
    }

    public function verifyOtpCode(Request $request)
    {
        $sessionCustomer = $request->session()->get('customer');

        if (empty($sessionCustomer['email'])) {
            return $this->redirectRoute('check-premium', errors: [
                'email' => 'Your session has expired. Please request an OTP again.',
            ]);
        }

        $validator = validator($request->only('otp_code'), [
            'otp_code' => ['required', 'string', 'regex:/^[0-9]{6}$/'],
        ]);

        if ($validator->fails()) {
            return $this->redirectRoute('otp-form', errors: $validator);
        }

        $otpCode = $validator->validated()['otp_code'];

        $customer = Customer::where('email',$sessionCustomer['email'])->first();

        if (!$customer) {
            $request->session()->forget('customer');

            return $this->redirectRoute('check-premium', errors: [
                'email' => 'Customer not found. Please request an OTP again.',
            ]);
        }

        if (!$customer->is_otp_sent or blank($customer->otp_code)) {
            return $this->redirectRoute('otp-form', errors: [
                'otp_code' => 'No active OTP code. Please request a new one.',
            ]);
        }

        if (!$customer->otp_expires_at or now()->greaterThanOrEqualTo($customer->otp_expires_at)) {
            return $this->redirectRoute('otp-form', errors: [
                'otp_code' => 'OTP code has expired. Please request a new one.',
            ]);
        }

        if ($otpCode !== (string) $customer->otp_code) {
            return $this->redirectRoute('otp-form', errors: [
                'otp_code' => 'Invalid OTP code.',
            ]);
        }

        $planId = PlanOccupation::where('occupation_slug', $sessionCustomer['occupation'] ?? '')->value('plan_id');

        $plan = $planId ? Plan::find($planId) : null;

        if (!$plan) {
            return $this->redirectRoute('check-premium', errors: [
                'occupation' => 'No plan was found for your occupation. Please select again.',
            ]);
        }

        $verifiedAt = now();
        $customer->update([
            'otp_verified_at' => $verifiedAt,
            'is_activated' => true,
            'otp_code' => null,
            'otp_expires_at' => null,
            'is_otp_sent' => false,
        ]);

        $request->session()->put('customer', array_merge($sessionCustomer, [
            'customer_id' => $customer->id,
            'otp_verified_at' => $verifiedAt->toDateTimeString(),
            'is_activated' => true,
            'plan' => $plan->toArray(),
        ]));

        return $this->redirectRoute('show-health-questions', flash: [
            'success' => 'OTP verified successfully.',
        ]);
    }

    public function showHealthQuestion(Request $request)
    {
        $customer = $request->session()->get('customer');

        if (empty($customer['email'])) {
            return $this->redirectRoute('check-premium', errors: [
                'email' => 'Please request an OTP first.',
            ]);
        }

        if (empty($customer['otp_verified_at'])) {
            return $this->redirectRoute('otp-form', errors: [
                'otp_code' => 'Please verify your OTP first.',
            ]);
        }

        if (empty($customer['plan'])) {
            return $this->redirectRoute('check-premium', errors: [
                'occupation' => 'Please select your occupation again.',
            ]);
        }

        $plan = $customer['plan'];
        $coverageFields = config('coverage_plans.coverage_fields', []);

        $coverageRows = collect($coverageFields)->map(function ($row) use ($plan) {
            $amount = data_get($plan, $row['field']);

            return [
                'label' => $row['label'],
                'amount' => $amount !== null ? number_format((float) $amount) . ' บาท' : '-',
            ];
        });
        
        return view('health-questions', [
            'customer' => $customer,
            'plan' => $plan,
            'selectedPlan' => $plan,
            'coverageRows' => $coverageRows,
        ]);
    }

    public function saveHealthQuestion(HealthQuestionRequest $request)
    {
        $customer = $request->session()->get('customer');
        $data = $request->validated();
        
        if (empty($customer['email'])) {
            return $this->redirectRoute('check-premium', errors: [
                'email' => 'Please request an OTP first.',
            ]);
        }

        if (empty($customer['otp_verified_at'])) {
            return $this->redirectRoute('otp-form', errors: [
                'otp_code' => 'Please verify your OTP first.',
            ]);
        }

        if (empty($customer['plan'])) {
            return $this->redirectRoute('check-premium', errors: [
                'occupation' => 'Please select your occupation again.',
            ]);
        }

        $request->session()->put('customer.health_questions', $data['health_questions']);

        return $this->redirectRoute('show-information-form',flash: [
            'success' => 'Health questions submitted successfully.',
        ]);
    }

    public function showInformationForm()
    {
        $customer = session('customer');
        
        return view('information-form', [
            'customer' => $customer,
            'cardTypes' => config('card_types.types', []),
            'nameTitles' => $this->getNameTitles(),
            'countries' => $this->getCountries(),
            'provinces' => $this->getProvinces(),
        ]);
    }

    public function saveInformation(SaveInformationRequest $request)
    {
        $data = $request->validated();
        $customer = $request->session()->get('customer');

        if (empty($customer['email'])) {
            return $this->redirectRoute('check-premium', errors: [
                'email' => 'Please request an OTP first.',
            ]);
        }

        if (empty($customer['otp_verified_at'])) {
            return $this->redirectRoute('otp-form', errors: [
                'otp_code' => 'Please verify your OTP first.',
            ]);
        }

        if (empty($customer['plan'])) {
            return $this->redirectRoute('check-premium', errors: [
                'occupation' => 'Please select your occupation again.',
            ]);
        }

        $data = $request->all();
        $request->session()->put('customer.information', $data);

        return $this->redirectRoute('show-review-information');
    }

    public function showReviewInformation(Request $request)
    {
        $customer = $request->session()->get('customer');
        
        if (empty($customer['email'])) {
            return $this->redirectRoute('check-premium', errors: [
                'email' => 'Please request an OTP first.',
            ]);
        }

        if (empty($customer['otp_verified_at'])) {
            return $this->redirectRoute('otp-form', errors: [
                'otp_code' => 'Please verify your OTP first.',
            ]);
        }

        if (empty($customer['plan'])) {
            return $this->redirectRoute('check-premium', errors: [
                'occupation' => 'Please select your occupation again.',
            ]);
        }

        return view('review-information', [
            'customer' => $customer,
        ]);
    }

    /*
    // Fix starting from here
    public function informationForm(Request $request)
    {
        

        // return view('information-form', [
        //     'customer' => [
        //         'occupation' => $request->input('occupation'),
        //         'email' => $request->input('email'),
        //         'date_of_birth' => $request->input('date_of_birth'),
        //         'otp_code' => $request->input('otp_code'),
        //         'selected_plan' => $request->input('selected_plan'),
        //         'health_questions' => $request->input('health_questions', []),
        //     ],
        //     'cardTypes' => $cardTypes,
        //     'nameTitles' => $this->getNameTitles(),
        //     'countries' => $this->getCountries(),
        //     'provinces' => $this->getProvinces(),
        // ]);
    }

    public function showInformationForm(Request $request)
    {
        // Get form
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
    */

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
