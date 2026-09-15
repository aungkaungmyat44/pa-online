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
                                        <h6>{{ $defaultPlan['title'] ?? 'อาชีพชั้น 1' }}</h6>
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

                        {{-- <div class="alert alert-danger mt-3 mb-0" role="alert">
                            <strong>Warning from the Office of Insurance Commission (OIC)</strong>
                            <span>
                                Concealing any facts or making any false statement may render this insurance contract voidable.
                                This may cause the insurer to deny liability under the insurance contract or cancel the insurance contract
                                in accordance with Section 865 of the Civil and Commercial Code.
                            </span>
                        </div> --}}

                        <div class="health-question-actions">
                            <a href="{{ route('check-premium') }}" class="otp-btn otp-btn-outline">ย้อนกลับ</a>
                            <button type="submit" class="check-premium-submit">ดำเนินการต่อ</button>
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
                <h2 class="modal-title" id="underwritingModalLabel">ข้อกำหนดและเงื่อนไข</h2>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <section>
                    <h6>เงื่อนไขการรับประกัน</h6>
                    <ul>
                        <li>ผู้เอาประกันภัย 1 ท่าน สามารถถือกรมธรรม์ภายใต้โครงการนี้ได้สูงสุด 1 ฉบับ</li>
                        <li>รับประกันภัยสำหรับผู้มีอายุ 16-60 ปีบริบูรณ์ โดยนับอายุตามวันเริ่มมีผลคุ้มครองของกรมธรรม์</li>
                        <li>กรมธรรม์จะเริ่มต้นความคุ้มครองในอีก 2 วัน นับถัดจากวันที่ชำระเบี้ยประกันภัยสำเร็จ</li>
                        <li>รับเฉพาะผู้เอาประกันภัยที่มีอาชีพชั้น 1 และชั้น 2 ตามหลักเกณฑ์ของบริษัทฯ</li>
                        <li>ผู้เอาประกันภัยต้องไม่เป็นผู้ทุพพลภาพถาวรก่อนวันเริ่มเอาประกันภัย</li>
                        <li>กรมธรรม์นี้เป็นกรมธรรม์ประกันภัยอุบัติเหตุส่วนบุคคลแบบรายปี</li>
                        <li>ค่ารักษาพยาบาลต้องเกิดขึ้นภายใน 52 สัปดาห์นับจากวันเกิดอุบัติเหตุ</li>
                        <li>กรณีมีสวัสดิการของรัฐ สวัสดิการอื่น หรือประกันภัยอื่น บริษัทฯ จะรับผิดชอบเฉพาะส่วนที่ขาด</li>
                        <li>ค่าห้องผู้ป่วยในไม่รวมกรณีค่าจ้างพยาบาลพิเศษ อุปกรณ์ค้ำยัน รถเข็นผู้ป่วย อวัยวะเทียมภายนอกร่างกาย แพทย์ทางเลือก และการฝังเข็ม</li>
                        <li>ผลประโยชน์กระดูกแตกหัก จำกัดการจ่ายสูงสุดไม่เกิน 100% ของจำนวนเงินเอาประกันภัย และต้องได้รับการยืนยันโดยภาพถ่ายทางรังสี (X-ray)</li>
                        <li>การเข้ารักษาตัวเป็นผู้ป่วยใน ต้องมีระยะเวลาติดต่อกันไม่น้อยกว่า 6 ชั่วโมง เว้นแต่เสียชีวิตระหว่างการรักษา</li>
                        <li>การเข้ารับการรักษาตั้งแต่ 2 ครั้งขึ้นไปจากสาเหตุเดียวกันภายในระยะเวลาที่บริษัทฯ กำหนด ให้ถือเป็นการรักษาครั้งเดียวกัน</li>
                    </ul>
                </section>
                <section>
                    <h6>ข้อยกเว้นการรับประกัน</h6>
                    <ul>
                        <li>การกระทำขณะอยู่ภายใต้ฤทธิ์สุรา สารเสพติด หรือยาเสพติดให้โทษ</li>
                        <li>การฆ่าตัวตาย พยายามฆ่าตัวตาย หรือทำร้ายร่างกายตนเอง</li>
                        <li>การติดเชื้อ โรค ปรสิต หรือการรักษาที่ไม่เกี่ยวข้องกับอุบัติเหตุที่ได้รับความคุ้มครอง</li>
                        <li>การแท้งลูก อาหารเป็นพิษ และการรักษาทันตกรรม เว้นแต่เป็นการรักษาจากอุบัติเหตุ</li>
                        <li>การปวดหลังจากโรคหรือภาวะเสื่อมของกระดูกสันหลัง</li>
                        <li>สงคราม การจลาจล การก่อการร้าย รังสีนิวเคลียร์ หรือกัมมันตภาพรังสี</li>
                        <li>การแข่งรถ กีฬาอันตราย ชกมวย โดดร่ม บันจี้จัมพ์ และดำน้ำที่ใช้ถังอากาศ</li>
                        <li>การขับขี่หรือโดยสารรถจักรยานยนต์ เว้นแต่มีการขยายความคุ้มครอง</li>
                        <li>การโดยสารอากาศยานที่ไม่ได้จดทะเบียนเพื่อบรรทุกผู้โดยสาร หรือไม่ได้ดำเนินการโดยสายการบินพาณิชย์</li>
                        <li>การทะเลาะวิวาท การก่ออาชญากรรม การถูกจับกุม หรือหลบหนีการจับกุม</li>
                        <li>การปฏิบัติหน้าที่ทางทหาร ตำรวจ หรืออาสาสมัครในปฏิบัติการสงครามหรือปราบปราม</li>
                        <li>โรคกระดูกพรุนบาง กระดูกหักจากพยาธิสภาพ หรือการแตกหักของกระดูกจากโรคที่เป็นมาโดยกำเนิด</li>
                        <li>สภาพการบาดเจ็บ หรือเจ็บป่วยที่เป็นมาก่อนการเอาประกันภัย</li>
                    </ul>
                </section>
                <section>
                    <h6>รายละเอียดอาชีพที่ไม่คุ้มครอง</h6>
                    <p>
                        รับจ้างทั่วไป,ผู้ปฏิบัติงานระเบิด/วัตถุระเบิด,ชาวประมง,พนักงานทำความสะอาดกระจก รวมถึงอาคารสูง,นักแข่งรถจักรยานยนต์,ช่างไฟฟ้าแรงสูง,พนักงานดับเพลิง,นักแสดงผาดโผนหรือสตั๊นท์แมน,นักแข่งรถ,นักมวย,นักประดาน้ำ,นักกีฬาเอ็กซ์ตรีม,นักกีฬาอาชีพ,นักปีนเขา,ผู้ปฏิบัติงานแท่นขุดเจาะ/แท่นผลิตกลางทะเล,คนงานแท่นขุดเจาะน้ำมัน,คนงานเหมืองใต้ดิน,คนงานเหมืองหิน,คนขับเรือ,เจ้าหน้าที่กู้ภัย,อาสาสมัครกู้ภัย,พนักงานติดตั้งเสาอากาศหรือป้ายโฆษณา,นักกีฬาต่อสู้/ศิลปะการต่อสู้อาชีพ,ทหาร/เจ้าหน้าที่ทหาร,ตำรวจ,นักบินและลูกเรือ,พยาบาล,แพทย์,ทันตแพทย์,จักษุแพทย์,ศัลยแพทย์,นักการเมือง,อาชีพอิสระ,ธุรกิจส่วนตัว,เจ้าของกิจการ,ค้าขาย,พนักงานX-RAY,พนักงานในโรงพยาบาล,พนักงานกายภาพบำบัด,ผู้ช่วยพยาบาล,ว่างงาน,นักเทคนิคการแพทย์,นักรังสีเทคนิค,ผู้ปฏิบัติงานเกี่ยวกับวัตถุระเบิด,คนงานรื้อถอนอาคาร,คนงานก่อสร้างบนที่สูง,ผู้ควบคุมปั้นจั่นหอสูง,ผู้ควบคุมเครื่องจักร/ปั้นจั่นหนัก,ช่างปฏิบัติงานสายไฟฟ้าแรงสูง,ผู้ปฏิบัติงานระบบไฟฟ้าแรงสูง,คนงานโรงงานเคมีที่มีความเสี่ยงสูง,ผู้ปฏิบัติงานกำจัดของเสียอันตราย,ผู้ฉีดพ่นสารกำจัดศัตรูพืช,ผู้ปฏิบัติงานกำจัดแมลง/สัตว์พาหะ,นักแสดงกายกรรม/ละครสัตว์,นักกายกรรม,นักกระโดดร่ม,นักร่มร่อน,นักบินเครื่องร่อน,นักปีนหน้าผาอาชีพ,คนงานก่อสร้างอุโมงค์ใต้ดิน,ช่างทำ/ซ่อมหลังคาที่ทำงานบนที่สูง,คนงานติดตั้ง/รื้อถอนนั่งร้าน,คนงานอู่ต่อเรือ
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
