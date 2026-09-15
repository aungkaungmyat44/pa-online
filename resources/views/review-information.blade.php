@extends('layout.master')

@section('title', 'ตรวจสอบข้อมูล')

@section('content')
@php
    $review = $review ?? [];
    $healthQuestions = $review['health_questions'] ?? [];
    $cardTypeLabels = [
        'National ID Card' => 'บัตรประจำตัวประชาชน',
        'Passport' => 'หนังสือเดินทาง',
        'Alien ID Card' => 'บัตรประจำตัวคนต่างด้าว',
        'Government / State Enterprise / Company / Partnership / Shop' => 'หน่วยงานราชการ / รัฐวิสาหกิจ / บริษัท / ห้างหุ้นส่วน / ร้านค้า',
        'Other' => 'อื่น ๆ',
    ];
    $healthQuestionLabels = [
        'other_insurance' => 'ท่านมีหรือได้ขอเอาประกันภัยอุบัติเหตุส่วนบุคคล หรือประกันชีวิตไว้กับบริษัทประกันภัยอื่นหรือไม่',
        'insurance_declined' => 'ท่านเคยถูกบริษัทประกันภัยปฏิเสธการรับประกันภัย ยกเลิกประกันภัย หรือเรียกเก็บเบี้ยประกันภัยเพิ่มสำหรับการประกันภัยดังกล่าวหรือไม่',
        'accident_hospitalized' => 'ในระยะเวลา 2 ปีที่ผ่านมา ท่านเคยได้รับบาดเจ็บจากอุบัติเหตุจนต้องเข้ารับการรักษาในโรงพยาบาลในฐานะผู้ป่วยในหรือไม่',
        'impairment_or_drug_history' => 'ท่านเคยมีหรือมีความผิดปกติของสายตา การได้ยิน หรือระบบประสาท เคยมีอวัยวะส่วนหนึ่งส่วนใดพิการ หรือเคยเสพสารเสพติดให้โทษร้ายแรง หรือเคยต้องโทษในคดีเกี่ยวกับยาเสพติดหรือไม่',
        'medical_condition_history' => 'ท่านเคยเป็น เคยได้รับการตรวจหรือรักษา กำลังรักษา หรือมีอาการหรือความผิดปกติที่เกี่ยวข้องกับโรคลมชัก โรคหัวใจ โรคความดันโลหิตสูง โรคเบาหวาน โรคกระดูกและ/หรือกล้ามเนื้อ โรคมะเร็ง โรคเอดส์ หรือมีเชื้อไวรัส HIV โรคหลอดเลือดสมอง หรือโรคพิษสุราเรื้อรังหรือไม่',
    ];
    $formatValue = function ($value) {
        if (is_array($value)) {
            return implode(', ', $value);
        }

        return filled($value) ? $value : '-';
    };
@endphp

<section id="check-premium-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <x-progress-steps active="review" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="check-premium-card review-information-card">
                    <h1>ตรวจสอบข้อมูล</h1>

                    <div class="review-section">
                        <h2>แผนที่เลือก</h2>
                        <div class="review-grid">
                            <div>
                                <span>แผนประกัน</span>
                                <strong>{{ $formatValue($review['selected_plan'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>อาชีพ</span>
                                <strong>{{ $formatValue($review['occupation'] ?? null) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>ข้อมูลส่วนตัว</h2>
                        <div class="review-grid">
                            <div>
                                <span>คำนำหน้า</span>
                                <strong>{{ $formatValue($review['prefix'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>ชื่อ</span>
                                <strong>{{ $formatValue($review['first_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>นามสกุล</span>
                                <strong>{{ $formatValue($review['last_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>สัญชาติ</span>
                                <strong>{{ $formatValue($review['nationality'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>ประเภทเอกสารยืนยันตัวตน</span>
                                <strong>{{ $formatValue($cardTypeLabels[$review['identity_type'] ?? ''] ?? ($review['identity_type'] ?? null)) }}</strong>
                            </div>
                            <div>
                                <span>เลขที่เอกสารยืนยันตัวตน</span>
                                <strong>{{ $formatValue($review['identity_number'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>วันเกิด</span>
                                <strong>{{ $formatValue($review['date_of_birth'] ?? null) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>ข้อมูลติดต่อ</h2>
                        <div class="review-grid">
                            <div>
                                <span>อีเมล</span>
                                <strong>{{ $formatValue($review['email'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>หมายเลขโทรศัพท์</span>
                                <strong>{{ $formatValue($review['phone_number'] ?? null) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>ข้อมูลที่อยู่</h2>
                        <div class="review-grid">
                            <div class="review-wide">
                                <span>ที่อยู่</span>
                                <strong>{{ $formatValue($review['full_address'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>จังหวัด</span>
                                <strong>{{ $formatValue($review['province_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>อำเภอ/เขต</span>
                                <strong>{{ $formatValue($review['district_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>ตำบล/แขวง</span>
                                <strong>{{ $formatValue($review['subdistrict_name'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>รหัสไปรษณีย์</span>
                                <strong>{{ $formatValue($review['zipcode'] ?? null) }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>แบบสอบถามสุขภาพ</h2>
                        <div class="review-grid">
                            @forelse ($healthQuestions as $question => $answer)
                                <div>
                                    <span>{{ $healthQuestionLabels[$question] ?? ucwords(str_replace('_', ' ', $question)) }}</span>
                                    <strong>{{ $answer }}</strong>
                                </div>
                            @empty
                                <div>
                                    <span>คำตอบแบบสอบถามสุขภาพ</span>
                                    <strong>-</strong>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="review-section">
                        <h2>ผู้รับผลประโยชน์และความยินยอม</h2>
                        <div class="review-grid">
                            <div>
                                <span>ผู้รับผลประโยชน์</span>
                                <strong>{{ $formatValue($review['beneficiary'] ?? null) }}</strong>
                            </div>
                            <div>
                                <span>การเก็บรวบรวมข้อมูลส่วนบุคคล</span>
                                <strong>{{ isset($review['personal_data_collection']) ? 'ยอมรับ' : '-' }}</strong>
                            </div>
                            <div>
                                <span>ข้อมูลส่วนบุคคลที่มีความอ่อนไหว</span>
                                <strong>{{ isset($review['sensitive_personal_data']) ? 'ยอมรับ' : '-' }}</strong>
                            </div>
                            <div>
                                <span>ความยินยอมด้านการตลาด</span>
                                <strong>{{ isset($review['marketing_consent']) ? 'ยอมรับ' : 'ไม่ยอมรับ' }}</strong>
                            </div>
                        </div>
                    </div>

                    <div class="review-section policy-document-section">
                        <h2>ท่านต้องการรับเอกสารกรมธรรม์อย่างไร</h2>
                        <div class="policy-document-options">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="policy_delivery[]" id="policy_delivery_email" value="รับกรมธรรม์ทางอีเมล" form="reviewConfirmForm" checked>
                                <label class="form-check-label" for="policy_delivery_email">รับกรมธรรม์ทางอีเมล</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" name="policy_delivery[]" id="policy_delivery_mail" value="รับกรมธรรม์ทางไปรษณีย์ภายใน 15 วัน" form="reviewConfirmForm">
                                <label class="form-check-label" for="policy_delivery_mail">รับกรมธรรม์ทางไปรษณีย์ภายใน 15 วัน</label>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('payment-methods') }}" method="post" class="review-confirm-form" id="reviewConfirmForm">
                        @csrf
                        @foreach ($review as $key => $value)
                            @if (is_array($value))
                                @foreach ($value as $childKey => $childValue)
                                    <input type="hidden" name="{{ $key }}[{{ $childKey }}]" value="{{ $childValue }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        <div class="health-question-actions">
                            <a href="{{ route('check-premium') }}" class="otp-btn otp-btn-outline">ย้อนกลับ</a>
                            <button type="submit" class="check-premium-submit">ยืนยัน</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
