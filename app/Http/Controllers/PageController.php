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
use App\Models\Order;
use App\Mail\OtpMail;
use Illuminate\Support\Facades\Mail;
use App\Services\EmailService;
use App\Services\KbankPaymentService;
use Carbon\Carbon;

class PageController extends Controller
{
    public function home()
    {
        $plans = Plan::orderBy('id', 'asc')->get();
        $coverageFields = config('coverage_plans.coverage_fields', []);

        return view('home', [
            'coveragePlans' => $plans->map(fn ($plan) => [
                'title' => $plan->name_th,
                'subtitle' => $plan->name_en,
                'value' => $plan->name_th,
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

        $customer = Customer::firstOrCreate(['email' => $email]);
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

            $sent = app(EmailService::class)->sendEmailApi($customer->email, $subject, $mailable->render());
            
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
            'occupation' => $data['occupation'],
            'email' => $email,
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
            'coverageRows' => $coverageRows
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
        $request->session()->put('customer.health_terms', $data['terms']);

        return $this->redirectRoute('show-information-form', flash: [
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
        $data = $request->all();
        $request->session()->put('customer.information', $data);
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

        $titleType = $customer['information']['title_type'] ?? null; 
        $cardType = $customer['information']['identity_type'];
        $idNo = $customer['information']['identity_number'];
        $cardCheckResult = false;
        
        if (($titleType == "P") or ($titleType == "C") or ($titleType == "O") or ($titleType == "G")) {
            $cardCheckResult =  true;

            if ($titleType == "P") {
                if ($cardType == "1") {
                    if (strlen($idNo) == 13) {
                        $cardCheckResult =  true;
                    }
                } elseif (($cardType == "2") or ($cardType == "3")) {
                    if (strlen($idNo) > 0) {
                        $cardCheckResult =  true;
                    }
                }
            }
            if ($titleType == "C") {
                if ($cardType == "4") {
                    if (strlen($idNo) >= 13) {
                        $cardCheckResult =  true;
                    }
                }
            }
        }
        
        if (!$cardCheckResult) {
            return $this->redirectRoute('show-information-form', errors: [
                'identity_type' => 'Customer card type is not valid.',
            ], input: $data);
        }
        
        $resultStatus = $this->validateIdentityCard($cardType, $idNo);
        
        if (!$resultStatus['status']) {
            return $this->redirectRoute('show-information-form', errors: [
                'identity_number' => 'Customer card type is not compatible with the ID number.',
            ], input: $data);
        }

        return $this->redirectRoute('show-review-information',flash: [
            'success' => 'Personal information submitted successfully.',
        ]);
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

        $occupation = PlanOccupation::where('occupation_slug', $customer['occupation'])->first();

        $namePrefix = $customer['information']['title_name'];
        $title = DB::connection('helperDB')
                ->table('title')
                ->where('name_s', $namePrefix)
                ->first();
        
        $countryCode = $customer['information']['nationality'];
        $country = DB::connection('helperDB')
            ->table('country')
            ->select('ct_code', 'ct_nameth', 'ct_nameeng')
            ->where('ct_code', $countryCode)
            ->first();
        
        return view('review-information', [
            'customer' => $customer,
            'occupation' => $occupation,
            'title' => $title,
            'country' => $country
        ])->with(['success' => 'Choose any payment method']);
    }

    public function proceedPayment(Request $request)
    {
        $data = $request->all();
        $customer = session()->get('customer');

        // Check previous order
        $oldOrder = Order::where('customer_id', $customer['customer_id'])
                         ->where('customer_id_type', $customer['information']['identity_type'])
                         ->where('customer_id_number', $customer['information']['identity_number'])
                         ->latest()
                         ->first();

        $orderPayload = [
            'plan_id' => $customer['plan']['id'],
            'customer_id' => $customer['customer_id'],
            'customer_id_type' => $customer['information']['identity_type'],
            'customer_id_number' => $customer['information']['identity_number'],
            'agent_no' => 'test-agent',
            'policy_no' => null,
            'order_info' => array_merge($data, $customer),
            'health_question_answers' => $customer['health_questions'],
            'effective_date' => Carbon::now()->format('Y-m-d H:i:s'),
            'expire_date' => Carbon::now()->addYear(1)->format('Y-m-d H:i:s'),
            'status' => 'pending',
            'premium_amount' => 888,
            'total_amount' => 888,
            'vat' => 0,
            'duty' => 0,
            'payment_method' => null,
            'payment_status' => 'unpaid',
            'paid_at' => null,
            'is_email_sent' => false,
            'policy_url' => null,
            'barcode_no' => null,
            'is_policy_generated' => false,
            'save_data_result' => null,
            'issue_policy_result' => null,
        ];

        if (empty($oldOrder)) {
            $order = Order::create(array_merge($orderPayload, [
                'order_unique_code' => $this->generateOrderNumber(),
            ]));
        } else {
            if ($oldOrder->status === 'delivered' and !empty(($oldOrder->policy_no))) {
                return $this->redirectRoute('show-review-information', errors: [
                    'order' => 'Customer has already purchased an active personal insurance policy.',
                ]);
            }

            $oldOrder->update($orderPayload);
            $order = $oldOrder;
        }
        
        session()->put('order', $order);
        return $this->redirectRoute('payment-method');
    }

    public function paymentMethod()
    {
        $sessionOrder = session()->get('order');
        $orderId = data_get($sessionOrder, 'id');

        if (empty($orderId)) {
            return $this->redirectRoute('check-premium', errors: [
                'order' => 'Order session timeout. Please start again.',
            ]);
        }

        $order = Order::find($orderId);
        
        if (empty($order)) {
            return $this->redirectRoute('check-premium', errors: [
                'order' => 'Order session timeout. Please start again.',
            ]);
        }

        return view('payment-methods', [
            'order' => $order->order_info ?? [],
        ]);
    }

    public function requestPayment(Request $request)
    {
        $sessionOrder = session()->get('order');
        $orderId = data_get($sessionOrder, 'id');

        if (empty($orderId)) {
            return $this->redirectRoute('check-premium', errors: [
                'order' => 'Order session timeout. Please start again.',
            ]);
        }

        $order = Order::find($orderId);
        
        if (empty($order)) {
            return $this->redirectRoute('check-premium', errors: [
                'order' => 'Order session timeout. Please start again.',
            ]);
        }
        
        $paymentMethod = $request->payment_method;
        $paymentService = new KbankPaymentService($paymentMethod);
        $checkoutData = $paymentService->checkout($order);
        $request->session()->put('customer.checkout_data', $checkoutData);

        return $this->redirectRoute('show-checkout', flash: [
            'success' => 'Please perform payment via pay button.',
        ]);
    }

    public function showCheckout()
    {
        dd("hello");
    }

    // Json Helpers
    public function getNameTitles()
    {
        return DB::connection('helperDB')
            ->table('title')
            ->select('title', 'name_s', 'titletype')
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

    private function generateOrderNumber(): string 
    {
		$orderNumber = "PA_" . date('YmdHis');
        
		if (app()->environment(['development', 'uat', 'local'])) {
			$orderNumber .= '_TEST';
		}

		return $orderNumber;
	}

    private function validateThai13DigitId(string $idNo): bool
    {
        if (preg_match('/^\d{13}$/', $idNo) !== 1) {
            return false;
        }

        $sum = 0;

        // Multiply the first 12 digits by 13, 12, 11 ... 2
        for ($i = 0; $i < 12; $i++) {
            $sum += (int) $idNo[$i] * (13 - $i);
        }

        $checkDigit = (11 - ($sum % 11)) % 10;

        return $checkDigit === (int) $idNo[12];
    }

    private function validateIdentityCard(string $cardType, string $idNo): array
    {
        // Remove spaces and hyphens before validation
        $idNo = strtoupper(preg_replace('/[\s-]+/', '', trim($idNo)));

        $messages = [
            "1" => 'Invalid Thai ID card number. It must contain 13 digits and have a valid checksum.',
            "2" => 'Invalid passport number. It should contain 6-9 English letters or numbers.',
            "3" => 'Invalid alien ID card number. It must contain 13 digits and have a valid checksum.',
            "4" => 'Invalid company/store ID. It must contain 13 digits and have a valid checksum.',
            "5" => 'Invalid other ID. It should contain 5-20 letters, numbers, hyphens, or slashes.'
        ];
        $message = $messages[$cardType] ?? t('invalid_card_type'); //'Invalid card type.'

        if (in_array($cardType, ["1", "3", "4"], true)) {
            $status = $this->validateThai13DigitId($idNo);
        } else if ($cardType == "2") {
            $status = preg_match('/^[A-Z0-9]{6,9}$/', $idNo) === 1;
        } else if ($cardType == "5") {
            $status = preg_match('/^[\p{L}\p{N}\/-]{5,20}$/u', $idNo) === 1;
        } else {
            $status = false;
        }

        return [
            'message' => $status ? '' : $message,
            'status'  => $status,
        ];
    }
}
