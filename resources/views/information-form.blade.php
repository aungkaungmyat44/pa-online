@extends('layout.master')

@section('title', 'ข้อมูลส่วนบุคคล')

@section('content')
@php
    $customer = $customer ?? [];
    $information = $customer['information'] ?? [];
    $cardTypeLabels = config('card_types.labels', []);
    $fieldValue = fn ($key, $default = '') => session()->hasOldInput($key)
        ? old($key)
        : data_get($information, $key, $default);
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
                    <div class="alert alert-warning d-none js-required-alert" role="alert">
                        Please fill in all required fields before continuing.
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <div class="fw-semibold mb-1">Please recheck the highlighted fields.</div>
                            <div>{{ $errors->first() }}</div>
                        </div>
                    @endif
                    <form action="{{ route('save-information') }}" method="POST" id="informationForm" novalidate>
                        @csrf
                        <input type="text" id="province_name" name="province_name" value="{{ $fieldValue('province_name') }}">
                        <input type="text" id="district_name" name="district_name" value="{{ $fieldValue('district_name') }}">
                        <input type="text" id="subdistrict_name" name="subdistrict_name" value="{{ $fieldValue('subdistrict_name') }}">
                        <input type="text" id="title_name" name="title_name" value="{{ $fieldValue('title_name') }}">
                        <input type="text" id="title_type" name="title_type" value="{{ $fieldValue('title_type') }}">
                        <div class="information-section">
                            <h2>ข้อมูลส่วนตัว</h2>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="prefix" class="form-label">คำนำหน้า<span class="text-danger"> *</span></label>
                                    <select class="form-control @error('prefix') is-invalid @enderror" id="prefix" name="prefix" required>
                                        <option value="">เลือกคำนำหน้า</option>
                                        @foreach (($nameTitles ?? []) as $nameTitle)
                                            <option value="{{ $nameTitle->name_s }}" data-title-type="{{ $nameTitle->titletype }}" @selected((string) $fieldValue('prefix') === (string) $nameTitle->name_s)>{{ $nameTitle->name_s }}</option>
                                        @endforeach
                                    </select>
                                    @error('prefix')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="first_name" class="form-label">ชื่อ<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ $fieldValue('first_name') }}" placeholder="กรอกชื่อ" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="last_name" class="form-label">นามสกุล<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ $fieldValue('last_name') }}" placeholder="กรอกนามสกุล" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
	                                <div class="col-md-4">
	                                    <label for="nationality" class="form-label">สัญชาติ<span class="text-danger"> *</span></label>
	                                    <select class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" required>
	                                        <option value="">เลือกสัญชาติ</option>
	                                        @foreach (($countries ?? []) as $country)
	                                            <option value="{{ $country->ct_code }}" @selected((string) $fieldValue('nationality', 'THA') === (string) $country->ct_code)>{{ ucwords(strtolower($country->ct_nameth)) }}</option>
	                                        @endforeach
	                                    </select>
                                        @error('nationality')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
	                                </div>
                                <div class="col-md-4">
                                    <label for="identity_type" class="form-label">ประเภทเอกสารยืนยันตัวตน<span class="text-danger"> *</span></label>
                                    <select class="form-control @error('identity_type') is-invalid @enderror" id="identity_type" name="identity_type" required>
                                        <option value="">เลือกประเภทเอกสาร</option>
                                        @foreach (($cardTypes ?? []) as $cardTypeId => $cardType)
                                            <option value="{{ $cardTypeId }}" @selected((string) $fieldValue('identity_type') === (string) $cardTypeId)>{{ $cardTypeLabels[$cardTypeId] ?? ucwords($cardType) }}</option>
                                        @endforeach
                                    </select>
                                    @error('identity_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="identity_number" class="form-label">เลขที่เอกสารยืนยันตัวตน<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control @error('identity_number') is-invalid @enderror" id="identity_number" name="identity_number" value="{{ $fieldValue('identity_number') }}" placeholder="กรอกเลขที่เอกสาร" required>
                                    @error('identity_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="date_of_birth" class="form-label">วันเกิด<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ $fieldValue('date_of_birth', $customer['date_of_birth'] ?? '') }}" readonly>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="information-section">
                            <h2>ข้อมูลติดต่อ</h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">อีเมล<span class="text-danger"> *</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $fieldValue('email', $customer['email'] ?? '') }}" readonly>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">หมายเลขโทรศัพท์<span class="text-danger"> *</span></label>
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ $fieldValue('phone_number') }}" placeholder="กรอกหมายเลขโทรศัพท์" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="information-section">
                            <h2>ข้อมูลที่อยู่</h2>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="full_address" class="form-label">ที่อยู่<span class="text-danger"> *</span></label>
                                    <textarea class="form-control @error('full_address') is-invalid @enderror" id="full_address" name="full_address" rows="3" placeholder="กรอกที่อยู่" required>{{ $fieldValue('full_address') }}</textarea>
                                    @error('full_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="province" class="form-label">จังหวัด<span class="text-danger"> *</span></label>
                                    <select class="form-control @error('province') is-invalid @enderror" id="province" name="province" required>
                                        <option value="">เลือกจังหวัด</option>
                                        @foreach (($provinces ?? []) as $province)
                                            <option value="{{ $province->province_code }}" @selected((string) $fieldValue('province') === (string) $province->province_code) data-province-name="{{ $province->province_name }}">{{ $province->province_name }}</option>
                                        @endforeach
                                    </select>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="district" class="form-label">อำเภอ/เขต<span class="text-danger"> *</span></label>
                                    <select class="form-control @error('district') is-invalid @enderror" id="district" name="district" required disabled>
                                        <option value="">เลือกอำเภอ/เขต</option>
                                    </select>
                                    @error('district')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="subdistrict" class="form-label">ตำบล/แขวง<span class="text-danger"> *</span></label>
                                    <select class="form-control @error('subdistrict') is-invalid @enderror" id="subdistrict" name="subdistrict" required disabled>
                                        <option value="">เลือกตำบล/แขวง</option>
                                    </select>
                                    @error('subdistrict')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="zipcode" class="form-label">รหัสไปรษณีย์<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode" value="{{ $fieldValue('zipcode') }}" placeholder="รหัสไปรษณีย์" 
                                        maxlength="6"
                                        inputmode="numeric"
                                        pattern="[0-9]{5,6}" 
                                        required
                                    >
                                    @error('zipcode')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="information-section">
                            <label>ผู้รับผลประโยชน์ <span class="text-danger">*หากเว้นว่างไว้ จะถือว่าผู้รับผลประโยชน์เป็นทายาทโดยธรรม*</span></label>
                            <input type="text" class="form-control @error('beneficiary') is-invalid @enderror" name="beneficiary" value="{{ $fieldValue('beneficiary') }}" placeholder="กรอกชื่อผู้รับผลประโยชน์">
                            @error('beneficiary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="information-section information-consent-section">
                            <h2>ความยินยอม</h2>
                            <div class="form-check">
                                <input class="form-check-input @error('personal_data_collection') is-invalid @enderror" type="checkbox" value="1" id="personal_data_collection" name="personal_data_collection" @checked($fieldValue('personal_data_collection')) required>
                                <label class="form-check-label" for="personal_data_collection">
                                    <span class="text-danger"> *</span>การเก็บรวบรวมข้อมูลส่วนบุคคล (ข้าพเจ้ายอมรับเงื่อนไขการประกันภัย และมีความประสงค์ขอเอาประกันภัยกับบริษัทฯ ตามเงื่อนไขของกรมธรรม์ประกันภัยที่บริษัทฯ ใช้สำหรับการประกันภัยนี้ โดยข้าพเจ้ารับรองว่า รายละเอียดและข้อความที่ข้าพเจ้าได้แถลงไว้ข้างต้นเป็นความจริง ถูกต้อง และครบถ้วนทุกประการ)
                                </label>
                                @error('personal_data_collection')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('sensitive_personal_data') is-invalid @enderror" type="checkbox" value="1" id="sensitive_personal_data" name="sensitive_personal_data" @checked($fieldValue('sensitive_personal_data')) required>
                                <label class="form-check-label" for="sensitive_personal_data">
                                    <span class="text-danger"> *</span>ข้อมูลส่วนบุคคลที่มีความอ่อนไหว(ข้าพเจ้าตกลงให้คำขอเอาประกันภัยฉบับนี้เป็นมูลฐานแห่งสัญญาประกันภัยระหว่างข้าพเจ้ากับบริษัทฯ หากปรากฏว่าข้อมูลหรือรายละเอียดที่ข้าพเจ้าแถลงไว้เป็นเท็จ หรือมีการปกปิดไม่เปิดเผยข้อเท็จจริง ข้าพเจ้ายินยอมให้บริษัทฯ บอกเลิกสัญญาประกันภัยได้)
                                </label>
                                @error('sensitive_personal_data')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('marketing_consent') is-invalid @enderror" type="checkbox" value="1" id="marketing_consent" name="marketing_consent" @checked($fieldValue('marketing_consent'))>
                                <label class="form-check-label" for="marketing_consent">
                                    ความยินยอมด้านการตลาด (ข้าพเจ้ายินยอมให้บริษัทฯ เก็บรวบรวม ใช้ และเปิดเผยข้อมูลเกี่ยวกับสุขภาพและข้อมูลส่วนบุคคลของข้าพเจ้าแก่สำนักงานคณะกรรมการกำกับและส่งเสริมการประกอบธุรกิจประกันภัย (คปภ.) เพื่อประโยชน์ในการกำกับดูแลธุรกิจประกันภัย)
                                </label>
                                @error('marketing_consent')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="health-question-actions">
                            <a href="{{ url()->previous() }}" class="otp-btn otp-btn-outline">ย้อนกลับ</a>
                            <button type="submit" class="check-premium-submit" id="informationSubmit">ดำเนินการต่อ</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
    $(function () {
        const informationForm = $('#informationForm');
        const informationSubmit = $('#informationSubmit');
        let currentProvinceCode = '';
        const oldProvince = @json($fieldValue('province'));
        const oldDistrict = @json($fieldValue('district'));
        const oldSubdistrict = @json($fieldValue('subdistrict'));
        const oldZipcode = @json($fieldValue('zipcode'));

        function showClientValidation(form) {
            const invalidFields = $(form).find(':input[required]').filter(function () {
                return !this.checkValidity();
            });

            $(form).find('.is-invalid').removeClass('is-invalid');
            $(form).find('.js-client-invalid-feedback').remove();

            if (!invalidFields.length) {
                $('.js-required-alert').addClass('d-none');
                return true;
            }

            $('.js-required-alert').removeClass('d-none');

            invalidFields.each(function () {
                const field = $(this);
                const formCheck = field.closest('.form-check');
                const message = this.validationMessage || 'This field is required.';
                field.addClass('is-invalid');

                if (!field.next('.invalid-feedback').length && !field.parent().find('.js-client-invalid-feedback').length) {
                    const feedback = $('<div class="invalid-feedback js-client-invalid-feedback d-block"></div>').text(message);

                    if (formCheck.length) {
                        formCheck.append(feedback);
                    } else {
                        feedback.insertAfter(field);
                    }
                }
            });

            invalidFields.first()[0].reportValidity();
            invalidFields.first()[0].scrollIntoView({ behavior: 'smooth', block: 'center' });

            return false;
        }

        function updateSubmitState() {
            const hasRequiredConsent = $('#personal_data_collection').is(':checked')
                && $('#sensitive_personal_data').is(':checked');

            informationSubmit.prop('disabled', !hasRequiredConsent);
        }

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
                const selected = String(provinceCode) === String(oldProvince) && String(district.district_code) === String(oldDistrict) ? ' selected' : '';
                options += '<option value="' + district.district_code + '"' + selected + '>' + district.district_name + '</option>';
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
                const selected = String(provinceCode) === String(oldProvince) && String(districtCode) === String(oldDistrict) && String(subdistrict.subdistrict_code) === String(oldSubdistrict) ? ' selected' : '';
                options += '<option value="' + subdistrict.subdistrict_code + '" data-zipcode="' + subdistrict.zipcode + '"' + selected + '>' + subdistrict.subdistrict_name + '</option>';
            });

            $('#subdistrict').html(options).prop('disabled', false);

            if (String(provinceCode) === String(oldProvince) && String(districtCode) === String(oldDistrict) && oldSubdistrict) {
                const selected = $('#subdistrict').find(':selected');
                $('#zipcode').val(oldZipcode || selected.data('zipcode') || '');
            }
        }

        $('#province').on('change', async function (event) {
            event.preventDefault();
            currentProvinceCode = $(this).val();
            $('#province_name').val($(this).find(':selected').text());
            $('#district_name').val('');
            $('#subdistrict_name').val('');
            await loadDistrict(currentProvinceCode);
        });

        $('#prefix').on('change', function () {
            const selectedTitle = $(this).find(':selected');
            $('#title_name').val(selectedTitle.text());
            $('#title_type').val(selectedTitle.data('title-type') || '');
        });

        if ($('#prefix').val() && !$('#title_type').val()) {
            $('#prefix').trigger('change');
        }

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

        informationForm.on('submit', function (event) {
            const selectedTitle = $('#prefix').find(':selected');
            $('#title_name').val(selectedTitle.text());
            $('#title_type').val(selectedTitle.data('title-type') || '');

            const selectedProvince = $('#province').find(':selected');
            const provinceName = selectedProvince.data('province-name') || selectedProvince.text();
            $('#province_name').val(provinceName);

            $('#district_name').val($('#district').find(':selected').text());
            $('#subdistrict_name').val($('#subdistrict').find(':selected').text());

            if (!showClientValidation(this)) {
                event.preventDefault();
            }
        });

        informationForm.find(':input[required]').on('input change', function () {
            if (this.checkValidity()) {
                $(this).removeClass('is-invalid');
                $(this).next('.js-client-invalid-feedback').remove();
            }

            if (!informationForm.find(':input[required]').filter(function () {
                return !this.checkValidity();
            }).length) {
                $('.js-required-alert').addClass('d-none');
            }
        });

        $('#personal_data_collection, #sensitive_personal_data').on('change', updateSubmitState);
        updateSubmitState();

        if (oldProvince) {
            currentProvinceCode = oldProvince;
            loadDistrict(oldProvince).then(async function () {
                if (oldDistrict) {
                    await loadSubdistricts(oldProvince, oldDistrict);
                }
            });
        }
    });
</script>
@endsection
