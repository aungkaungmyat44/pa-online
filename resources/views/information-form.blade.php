@extends('layout.master')

@section('title', 'ข้อมูลส่วนบุคคล')

@section('content')
@php
    $customer = $customer ?? [];
    $cardTypeLabels = [
        'National ID Card' => 'บัตรประจำตัวประชาชน',
        'Passport' => 'หนังสือเดินทาง',
        'Alien ID Card' => 'บัตรประจำตัวคนต่างด้าว',
        'Government / State Enterprise / Company / Partnership / Shop' => 'หน่วยงานราชการ / รัฐวิสาหกิจ / บริษัท / ห้างหุ้นส่วน / ร้านค้า',
        'Other' => 'อื่น ๆ',
    ];
@endphp

<section id="check-premium-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <x-progress-steps active="information" />
            </div>
        </div>

        <div class="row">
            <div class="col-lg-10 mx-auto">
                <div class="check-premium-card information-form-card">
                    <h1>ข้อมูลส่วนบุคคล</h1>
                    <form action="{{ route('review-information') }}" method="POST">
                        @csrf
                        <input type="hidden" name="occupation" value="{{ $customer['occupation'] ?? '' }}">
                        <input type="hidden" name="otp_code" value="{{ $customer['otp_code'] ?? '' }}">
                        <input type="hidden" name="selected_plan" value="{{ $customer['selected_plan'] ?? '' }}">
                        <input type="hidden" id="province_name" name="province_name" value="">
                        <input type="hidden" id="district_name" name="district_name" value="">
                        <input type="hidden" id="subdistrict_name" name="subdistrict_name" value="">
                        @foreach (($customer['health_questions'] ?? []) as $key => $answer)
                            <input type="hidden" name="health_questions[{{ $key }}]" value="{{ $answer }}">
                        @endforeach

                        <div class="information-section">
                            <h2>ข้อมูลส่วนตัว</h2>
                            <div class="row g-3">
                                <div class="col-md-4">
	                                    <label for="prefix" class="form-label">คำนำหน้า</label>
	                                    <select class="form-control" id="prefix" name="prefix" required>
	                                        <option value="">เลือกคำนำหน้า</option>
	                                        @foreach (($nameTitles ?? []) as $nameTitle)
	                                            <option value="{{ $nameTitle->title }}">{{ $nameTitle->name_s }}</option>
	                                        @endforeach
	                                    </select>
	                                </div>
                                <div class="col-md-4">
                                    <label for="first_name" class="form-label">ชื่อ</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="กรอกชื่อ" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="last_name" class="form-label">นามสกุล</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="กรอกนามสกุล" required>
                                </div>
	                                <div class="col-md-4">
	                                    <label for="nationality" class="form-label">สัญชาติ</label>
	                                    <select class="form-control" id="nationality" name="nationality" required>
	                                        <option value="">เลือกสัญชาติ</option>
	                                        @foreach (($countries ?? []) as $country)
	                                            <option value="{{ $country->ct_code }}">{{ ucwords(strtolower($country->ct_nameth)) }}</option>
	                                        @endforeach
	                                    </select>
	                                </div>
                                <div class="col-md-4">
                                    <label for="identity_type" class="form-label">ประเภทเอกสารยืนยันตัวตน</label>
                                    <select class="form-control" id="identity_type" name="identity_type" required>
                                        <option value="">เลือกประเภทเอกสาร</option>
                                        @foreach (($cardTypes ?? []) as $cardType)
                                            <option value="{{ $cardType }}">{{ $cardTypeLabels[$cardType] ?? $cardType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="identity_number" class="form-label">เลขที่เอกสารยืนยันตัวตน</label>
                                    <input type="text" class="form-control" id="identity_number" name="identity_number" placeholder="กรอกเลขที่เอกสาร" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="date_of_birth" class="form-label">วันเกิด</label>
                                    <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $customer['date_of_birth'] ?? '' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="information-section">
                            <h2>ข้อมูลติดต่อ</h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">อีเมล</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $customer['email'] ?? '' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">หมายเลขโทรศัพท์</label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="กรอกหมายเลขโทรศัพท์" required>
                                </div>
                            </div>
                        </div>

                        <div class="information-section">
                            <h2>ข้อมูลที่อยู่</h2>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="full_address" class="form-label">ที่อยู่</label>
                                    <textarea class="form-control" id="full_address" name="full_address" rows="3" placeholder="กรอกที่อยู่" required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="province" class="form-label">จังหวัด</label>
                                    <select class="form-control" id="province" name="province" required>
                                        <option value="">เลือกจังหวัด</option>
                                        @foreach (($provinces ?? []) as $province)
                                            <option value="{{ $province->province_code }}">{{ $province->province_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="district" class="form-label">อำเภอ/เขต</label>
                                    <select class="form-control" id="district" name="district" required disabled>
                                        <option value="">เลือกอำเภอ/เขต</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="subdistrict" class="form-label">ตำบล/แขวง</label>
                                    <select class="form-control" id="subdistrict" name="subdistrict" required disabled>
                                        <option value="">เลือกตำบล/แขวง</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="zipcode" class="form-label">รหัสไปรษณีย์</label>
                                    <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="รหัสไปรษณีย์" required>
                                </div>
                            </div>
                        </div>

                        <div class="information-section">
                            <h2>ผู้รับผลประโยชน์</h2>
                            <input type="text" class="form-control" name="beneficiary" value="ทายาทโดยธรรม" readonly>
                        </div>

                        <div class="information-section information-consent-section">
                            <h2>ความยินยอม</h2>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="personal_data_collection" name="personal_data_collection" required>
                                <label class="form-check-label" for="personal_data_collection">
                                    การเก็บรวบรวมข้อมูลส่วนบุคคล (ข้าพเจ้ายอมรับเงื่อนไขการประกันภัย และมีความประสงค์ขอเอาประกันภัยกับบริษัทฯ ตามเงื่อนไขของกรมธรรม์ประกันภัยที่บริษัทฯ ใช้สำหรับการประกันภัยนี้ โดยข้าพเจ้ารับรองว่า รายละเอียดและข้อความที่ข้าพเจ้าได้แถลงไว้ข้างต้นเป็นความจริง ถูกต้อง และครบถ้วนทุกประการ)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="sensitive_personal_data" name="sensitive_personal_data" required>
                                <label class="form-check-label" for="sensitive_personal_data">
                                    ข้อมูลส่วนบุคคลที่มีความอ่อนไหว(ข้าพเจ้าตกลงให้คำขอเอาประกันภัยฉบับนี้เป็นมูลฐานแห่งสัญญาประกันภัยระหว่างข้าพเจ้ากับบริษัทฯ หากปรากฏว่าข้อมูลหรือรายละเอียดที่ข้าพเจ้าแถลงไว้เป็นเท็จ หรือมีการปกปิดไม่เปิดเผยข้อเท็จจริง ข้าพเจ้ายินยอมให้บริษัทฯ บอกเลิกสัญญาประกันภัยได้)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="marketing_consent" name="marketing_consent">
                                <label class="form-check-label" for="marketing_consent">
                                    ความยินยอมด้านการตลาด (<span class="text-danger">ไม่บังคับ</span> - ข้าพเจ้ายินยอมให้บริษัทฯ เก็บรวบรวม ใช้ และเปิดเผยข้อมูลเกี่ยวกับสุขภาพและข้อมูลส่วนบุคคลของข้าพเจ้าแก่สำนักงานคณะกรรมการกำกับและส่งเสริมการประกอบธุรกิจประกันภัย (คปภ.) เพื่อประโยชน์ในการกำกับดูแลธุรกิจประกันภัย)
                                </label>
                            </div>
                        </div>

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
<script>
    $(function () {
        let currentProvinceCode = '';

        function resetDistrict() {
            $('#district')
                .html('<option value="">เลือกอำเภอ/เขต</option>')
                .prop('disabled', true);
        }

        function resetSubdistrict() {
            $('#subdistrict')
                .html('<option value="">เลือกตำบล/แขวง</option>')
                .prop('disabled', true);
            $('#zipcode').val('');
        }

        async function loadDistrict(provinceCode) {
            resetDistrict();
            resetSubdistrict();

            if (!provinceCode) {
                return;
            }

            const response = await getData('{{ route('districts') }}', {
                province_code: provinceCode
            });

            const districts = response.data || [];
            let options = '<option value="">เลือกอำเภอ/เขต</option>';

            districts.forEach(function (district) {
                options += '<option value="' + district.district_code + '">' + district.district_name + '</option>';
            });

            $('#district').html(options).prop('disabled', false);
        }

        async function loadSubdistricts(provinceCode, districtCode) {
            resetSubdistrict();

            if (!provinceCode || !districtCode) {
                return;
            }

            const response = await getData('{{ route('subdistricts') }}', {
                province_code: provinceCode,
                district_code: districtCode
            });

            const subdistricts = response.data || [];
            let options = '<option value="">เลือกตำบล/แขวง</option>';

            subdistricts.forEach(function (subdistrict) {
                options += '<option value="' + subdistrict.subdistrict_code + '" data-zipcode="' + subdistrict.zipcode + '">' + subdistrict.subdistrict_name + '</option>';
            });

            $('#subdistrict').html(options).prop('disabled', false);
        }

        $('#province').on('change', async function (event) {
            event.preventDefault();
            currentProvinceCode = $(this).val();
            $('#province_name').val($(this).find(':selected').text());
            $('#district_name').val('');
            $('#subdistrict_name').val('');
            await loadDistrict(currentProvinceCode);
        });

        $('#district').on('change', async function (event) {
            event.preventDefault();
            $('#district_name').val($(this).find(':selected').text());
            $('#subdistrict_name').val('');
            await loadSubdistricts(currentProvinceCode, $(this).val());
        });

        $('#subdistrict').on('change', function () {
            const selected = $(this).find(':selected');
            $('#subdistrict_name').val(selected.text());
            $('#zipcode').val(selected.data('zipcode') || '');
        });
    });
</script>
@endsection
