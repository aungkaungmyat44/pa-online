@extends('layout.master')

@section('title', 'Health Questions')

@section('content')
@php
    $customer = $customer ?? [];
    $selectedPlan = $customer['selected_plan'] ?? config('coverage_plans.default_plan');
    $defaultPlanIndex = 0;
    $defaultPlan = config('coverage_plans.plans.' . $defaultPlanIndex, []);
    $coverageRows = config('coverage_plans.rows', []);
    $questions = [
        'other_insurance' => [
            'text' => 'ท่านมีหรือได้ขอเอาประกันภัยอุบัติเหตุส่วนบุคคล หรือประกันชีวิตไว้กับบริษัทประกันภัยอื่นหรือไม่',
            'options' => ['ไม่มี', 'มี'],
        ],
        'insurance_declined' => [
            'text' => 'ท่านเคยถูกบริษัทประกันภัยปฏิเสธการรับประกันภัย ยกเลิกประกันภัย หรือเรียกเก็บเบี้ยประกันภัยเพิ่มสำหรับการประกันภัยดังกล่าวหรือไม่',
            'options' => ['ไม่เคย', 'เคย'],
        ],
        'accident_hospitalized' => [
            'text' => 'ในระยะเวลา 2 ปีที่ผ่านมา ท่านเคยได้รับบาดเจ็บจากอุบัติเหตุจนต้องเข้ารับการรักษาในโรงพยาบาลในฐานะผู้ป่วยในหรือไม่',
            'options' => ['ไม่เคย', 'เคย'],
        ],
        'impairment_or_drug_history' => [
            'text' => 'ท่านเคยมีหรือมีความผิดปกติของสายตา การได้ยิน หรือระบบประสาท เคยมีอวัยวะส่วนหนึ่งส่วนใดพิการ หรือเคยเสพสารเสพติดให้โทษร้ายแรง หรือเคยต้องโทษในคดีเกี่ยวกับยาเสพติดหรือไม่',
            'options' => ['ไม่เคย', 'เคย'],
        ],
        'medical_condition_history' => [
            'text' => 'ท่านเคยเป็น เคยได้รับการตรวจหรือรักษา กำลังรักษา หรือมีอาการหรือความผิดปกติที่เกี่ยวข้องกับโรคลมชัก โรคหัวใจ โรคความดันโลหิตสูง โรคเบาหวาน โรคกระดูกและ/หรือกล้ามเนื้อ โรคมะเร็ง โรคเอดส์ หรือมีเชื้อไวรัส HIV โรคหลอดเลือดสมอง หรือโรคพิษสุราเรื้อรังหรือไม่',
            'options' => ['ไม่เคย', 'เคย'],
        ],
    ];
@endphp

<section id="check-premium-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <x-progress-steps active="questionnaire" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="check-premium-card health-question-card">
                    <h1>แบบสอบถามสุขภาพ</h1>
                    <form action="{{ route('information-form') }}" method="POST">
                        @csrf
                        <input type="hidden" name="occupation" value="{{ $customer['occupation'] ?? '' }}">
                        <input type="hidden" name="email" value="{{ $customer['email'] ?? '' }}">
                        <input type="hidden" name="date_of_birth" value="{{ $customer['date_of_birth'] ?? '' }}">
                        <input type="hidden" name="otp_code" value="{{ $customer['otp_code'] ?? '' }}">
                        <input type="hidden" name="selected_plan" value="{{ $selectedPlan }}">

                        <div class="health-plan-section">
                            <h2>แผนความคุ้มครอง</h2>
                            <div class="health-plan-card health-plan-card-readonly">
                                <div class="health-plan-summary">
                                    <div>
                                        <h3>{{ $defaultPlan['title'] ?? 'อาชีพชั้น 1' }}</h3>
                                        <strong>{{ $defaultPlan['subtitle'] ?? 'Class1' }}</strong>
                                    </div>
                                    <span>เลือกอัตโนมัติ</span>
                                </div>
                                <ul>
                                    @foreach ($coverageRows as $row)
                                        <li>{{ $row['label'] }}: {{ $row['amounts'][$defaultPlanIndex] ?? '-' }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>

                        <div class="health-question-list">
                            @foreach ($questions as $key => $question)
                                <div class="health-question-item">
                                    <p>{{ $loop->iteration }}. {{ $question['text'] }}</p>
                                    <div class="health-radio-group" role="radiogroup" aria-label="{{ $question['text'] }}">
                                        @foreach ($question['options'] as $option)
                                            @php
                                                $optionId = $key . '_' . $loop->index;
                                            @endphp
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="health_questions[{{ $key }}]" id="{{ $optionId }}" value="{{ $option }}" required>
                                                <label class="form-check-label" for="{{ $optionId }}">{{ $option }}</label>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-check check-premium-terms mt-3">
                            <input class="form-check-input" type="checkbox" value="1" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                ข้าพเจ้าเข้าใจ <button type="button" class="terms-link" data-bs-toggle="modal" data-bs-target="#underwritingModal">ข้อกำหนดและเงื่อนไข</button> โดยสหมงคลประกันภัย
                            </label>
                        </div>

                        <div class="alert alert-danger mt-3 mb-0" role="alert">
                            <strong>Warning from the Office of Insurance Commission (OIC)</strong>
                            <span>
                                Concealing any facts or making any false statement may render this insurance contract voidable.
                                This may cause the insurer to deny liability under the insurance contract or cancel the insurance contract
                                in accordance with Section 865 of the Civil and Commercial Code.
                            </span>
                        </div>

                        <div class="health-question-actions">
                            <a href="{{ route('check-premium') }}" class="otp-btn otp-btn-outline">Back</a>
                            <button type="submit" class="check-premium-submit">Continue</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<div class="modal fade" id="underwritingModal" tabindex="-1" aria-labelledby="underwritingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg">
        <div class="modal-content underwriting-modal">
            <div class="modal-header">
                <h2 class="modal-title" id="underwritingModalLabel">Terms and Conditions</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section>
                    <h3>Underwriting Conditions</h3>
                    <ul>
                        <li>Each Insured may hold a maximum of one (1) Policy under this insurance scheme.</li>
                        <li>Coverage is available for persons aged 1 to 75 years, calculated as at the commencement date of the Period of Insurance.</li>
                        <li>Coverage is available only to applicants classified as Occupation Class 1 or Class 2, in accordance with the Company's underwriting guidelines.</li>
                        <li>The Insured must not be suffering from Total Permanent Disability prior to the commencement of the Period of Insurance.</li>
                        <li>This Policy is issued as an Annual Personal Accident Insurance Policy.</li>
                        <li>Medical Expenses must be incurred within 52 weeks from the date of the Accident.</li>
                        <li>Where the Insured is entitled to reimbursement under any government welfare scheme, other welfare scheme, or other insurance, the Company shall be liable only for the balance of the eligible expenses remaining unpaid.</li>
                        <li>Inpatient Room and Board benefits do not cover charges for private duty nursing, supporting appliances (except crutches), wheelchairs, external prosthetic devices, alternative medicine, or acupuncture.</li>
                        <li>The Fractured Bones Benefit is payable up to a maximum of 100% of the Sum Insured and is subject to confirmation by X-ray examination.</li>
                        <li>Admission as an Inpatient must be for a continuous period of not less than six (6) hours, unless the Insured dies during such hospitalization.</li>
                        <li>Two (2) or more treatments arising from the same cause and received within the period specified by the Company shall be deemed to constitute a single course of treatment.</li>
                    </ul>
                </section>
                <section>
                    <h3>Underwriting Exclusions</h3>
                    <ul>
                        <li>Any act committed whilst under the influence of alcohol, narcotic drugs, or addictive substances.</li>
                        <li>Suicide, attempted suicide, or intentional self-inflicted injury.</li>
                        <li>Infection, disease, parasites, or medical treatment not directly related to a covered Accidental Bodily Injury.</li>
                        <li>Miscarriage, food poisoning, and dental treatment, unless such dental treatment is required as a direct result of an Accident.</li>
                        <li>Back pain arising from disease or degenerative disorders of the spine.</li>
                        <li>War, riot, terrorism, nuclear radiation, or radioactive contamination.</li>
                        <li>Motor racing, hazardous sports, boxing, parachuting, bungee jumping, and scuba diving using breathing apparatus.</li>
                        <li>Driving or riding as a passenger on a motorcycle, unless Extension of Coverage has been purchased.</li>
                        <li>Travelling in any aircraft not licensed for the carriage of passengers or not operated by a commercial airline.</li>
                        <li>Participation in a fight, commission of a serious criminal offence, arrest, or evasion of arrest.</li>
                        <li>Military, police, or volunteer duties involving war or suppression operations.</li>
                        <li>Osteoporosis, pathological fractures, or fractures resulting from congenital diseases or disorders.</li>
                        <li>Any Pre-existing Injury or Pre-existing Illness prior to the commencement of the Period of Insurance.</li>
                    </ul>
                </section>
                <section>
                    <h3>Occupations Exclusions</h3>
                    <p>
                        รับจ้างทั่วไป,ผู้ปฏิบัติงานระเบิด/วัตถุระเบิด,ชาวประมง,พนักงานทำความสะอาดกระจก รวมถึงอาคารสูง,นักแข่งรถจักรยานยนต์,ช่างไฟฟ้าแรงสูง,พนักงานดับเพลิง,นักแสดงผาดโผนหรือสตั๊นท์แมน,นักแข่งรถ,นักมวย,นักประดาน้ำ,นักกีฬาเอ็กซ์ตรีม,นักกีฬาอาชีพ,นักปีนเขา,ผู้ปฏิบัติงานแท่นขุดเจาะ/แท่นผลิตกลางทะเล,คนงานแท่นขุดเจาะน้ำมัน,คนงานเหมืองใต้ดิน,คนงานเหมืองหิน,คนขับเรือ,เจ้าหน้าที่กู้ภัย,อาสาสมัครกู้ภัย,พนักงานติดตั้งเสาอากาศหรือป้ายโฆษณา,นักกีฬาต่อสู้/ศิลปะการต่อสู้อาชีพ,ทหาร/เจ้าหน้าที่ทหาร,ตำรวจ,นักบินและลูกเรือ,พยาบาล,แพทย์,ทันตแพทย์,จักษุแพทย์,ศัลยแพทย์,นักการเมือง,อาชีพอิสระ,ธุรกิจส่วนตัว,เจ้าของกิจการ,ค้าขาย,พนักงานX-RAY,พนักงานในโรงพยาบาล,พนักงานกายภาพบำบัด,ผู้ช่วยพยาบาล,ว่างงาน,นักเทคนิคการแพทย์,นักรังสีเทคนิค,ผู้ปฏิบัติงานเกี่ยวกับวัตถุระเบิด,คนงานรื้อถอนอาคาร,คนงานก่อสร้างบนที่สูง,ผู้ควบคุมปั้นจั่นหอสูง,ผู้ควบคุมเครื่องจักร/ปั้นจั่นหนัก,ช่างปฏิบัติงานสายไฟฟ้าแรงสูง,ผู้ปฏิบัติงานระบบไฟฟ้าแรงสูง,คนงานโรงงานเคมีที่มีความเสี่ยงสูง,ผู้ปฏิบัติงานกำจัดของเสียอันตราย,ผู้ฉีดพ่นสารกำจัดศัตรูพืช,ผู้ปฏิบัติงานกำจัดแมลง/สัตว์พาหะ,นักแสดงกายกรรม/ละครสัตว์,นักกายกรรม,นักกระโดดร่ม,นักร่มร่อน,นักบินเครื่องร่อน,นักปีนหน้าผาอาชีพ,คนงานก่อสร้างอุโมงค์ใต้ดิน,ช่างทำ/ซ่อมหลังคาที่ทำงานบนที่สูง,คนงานติดตั้ง/รื้อถอนนั่งร้าน,คนงานอู่ต่อเรือ
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
