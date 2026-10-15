@extends('layout.master')

@section('title', __('content.information.title'))

@section('content')
@php
    $customer = $customer ?? [];
    $information = $customer['information'] ?? [];
    $cardTypeLabels = __('content.information.document_types');
    $fieldValue = fn ($key, $default = '') => session()->hasOldInput($key)
        ? old($key)
        : data_get($information, $key, $default);
    $activeLang = app()->getLocale();
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
                    <h1>{{ __('content.information.title') }}</h1>
                    <div class="alert alert-warning d-none js-required-alert" role="alert">
                        {{ __('content.information.required_alert') }}
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <div class="fw-semibold mb-1">
                                {{ __('content.information.recheck_fields') }}
                            </div>
                            <div>{{ $errors->first() }}</div>
                        </div>
                    @endif
                    <form action="{{ route('save-information') }}" method="POST" id="informationForm" novalidate>
                        @csrf
                        <input type="hidden" id="province_name" name="province_name" value="{{ $fieldValue('province_name') }}">
                        <input type="hidden" id="district_name" name="district_name" value="{{ $fieldValue('district_name') }}">
                        <input type="hidden" id="subdistrict_name" name="subdistrict_name" value="{{ $fieldValue('subdistrict_name') }}">
                        <input type="hidden" id="title_name" name="title_name" value="{{ $fieldValue('title_name') }}">
                        <input type="hidden" id="title_type" name="title_type" value="{{ $fieldValue('title_type') }}">
                        <div class="information-section">
                            <h2>{{ __('content.information.personal_details') }}</h2>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label for="prefix" class="form-label">{{ __('content.information.fields.prefix') }}<span class="text-danger"> *</span></label>
                                    <select class="form-control @error('prefix') is-invalid @enderror" id="prefix" name="prefix" required>
                                        <option value="">{{ __('content.information.placeholders.prefix') }}</option>
                                        @foreach (($nameTitles ?? []) as $nameTitle)
                                            <option value="{{ $nameTitle->name_s }}" data-title-type="{{ $nameTitle->titletype }}" @selected((string) $fieldValue('prefix') === (string) $nameTitle->name_s)>{{ $nameTitle->display_name ?: $nameTitle->name_s }}</option>
                                        @endforeach
                                    </select>
                                    @error('prefix')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="first_name" class="form-label">{{ __('content.information.fields.first_name') }}<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control @error('first_name') is-invalid @enderror" id="first_name" name="first_name" value="{{ $fieldValue('first_name') }}" placeholder="{{ __('content.information.placeholders.first_name') }}" required>
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="last_name" class="form-label">{{ __('content.information.fields.last_name') }}<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control @error('last_name') is-invalid @enderror" id="last_name" name="last_name" value="{{ $fieldValue('last_name') }}" placeholder="{{ __('content.information.placeholders.last_name') }}" required>
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
	                                <div class="col-md-4">
	                                    <label for="nationality" class="form-label">{{ __('content.information.fields.nationality') }}<span class="text-danger"> *</span></label>
	                                    <select class="form-control @error('nationality') is-invalid @enderror" id="nationality" name="nationality" required>
	                                        <option value="">{{ __('content.information.placeholders.nationality') }}</option>
	                                        @foreach (($countries ?? []) as $country)
	                                            <option value="{{ $country->ct_code }}" @selected((string) $fieldValue('nationality', 'THA') === (string) $country->ct_code)>
                                                    {{ ucwords(strtolower(app()->getLocale() === 'en' ? ($country->ct_nameeng ?: $country->ct_nameth) : $country->ct_nameth)) }}
                                                </option>
	                                        @endforeach
	                                    </select>
                                        @error('nationality')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
	                                </div>
                                <div class="col-md-4">
                                    <label for="identity_type" class="form-label">{{ __('content.information.fields.identity_type') }}<span class="text-danger"> *</span></label>
                                    <select class="form-control @error('identity_type') is-invalid @enderror" id="identity_type" name="identity_type" required>
                                        <option value="">{{ __('content.information.placeholders.identity_type') }}</option>
                                        @foreach (($cardTypes ?? []) as $cardTypeId => $cardType)
                                            <option value="{{ $cardTypeId }}" @selected((string) $fieldValue('identity_type') === (string) $cardTypeId)>{{ $cardTypeLabels[$cardTypeId] ?? ucwords($cardType) }}</option>
                                        @endforeach
                                    </select>
                                    @error('identity_type')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="identity_number" class="form-label">{{ __('content.information.fields.identity_number') }}<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control @error('identity_number') is-invalid @enderror" id="identity_number" name="identity_number" value="{{ $fieldValue('identity_number') }}" placeholder="{{ __('content.information.placeholders.identity_number') }}" required>
                                    @error('identity_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="date_of_birth" class="form-label">{{ __('content.information.fields.date_of_birth') }}<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control @error('date_of_birth') is-invalid @enderror" id="date_of_birth" name="date_of_birth" value="{{ $fieldValue('date_of_birth', $customer['date_of_birth'] ?? '') }}" readonly>
                                    @error('date_of_birth')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="information-section">
                            <h2>{{ __('content.information.contact_details') }}</h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">{{ __('content.information.fields.email') }}<span class="text-danger"> *</span></label>
                                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $fieldValue('email', $customer['email'] ?? '') }}" readonly>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">{{ __('content.information.fields.phone_number') }}<span class="text-danger"> *</span></label>
                                    <input type="tel" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ $fieldValue('phone_number') }}" placeholder="{{ __('content.information.placeholders.phone_number') }}" required>
                                    @error('phone_number')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="information-section">
                            <h2>{{ __('content.information.address_details') }}</h2>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="full_address" class="form-label">{{ __('content.information.fields.full_address') }}<span class="text-danger"> *</span></label>
                                    <textarea class="form-control @error('full_address') is-invalid @enderror" id="full_address" name="full_address" rows="3" placeholder="{{ __('content.information.placeholders.full_address') }}" required>{{ $fieldValue('full_address') }}</textarea>
                                    @error('full_address')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="province" class="form-label">{{ __('content.information.fields.province') }}<span class="text-danger"> *</span></label>
                                    <select class="form-control @error('province') is-invalid @enderror" id="province" name="province" required>
                                        <option value="">{{ __('content.information.placeholders.province') }}</option>
                                        @foreach (($provinces ?? []) as $province)
                                            <option value="{{ $province->province_code }}" @selected((string) $fieldValue('province') === (string) $province->province_code) data-province-name="{{ $province->province_name }}">
                                                @if($activeLang == 'th')
                                                {{ $province->province_name }}
                                                @else
                                                {{ $province->province_name_en }}
                                                @endif
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('province')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="district" class="form-label">{{ __('content.information.fields.district') }}<span class="text-danger"> *</span></label>
                                    <select class="form-control @error('district') is-invalid @enderror" id="district" name="district" required disabled>
                                        <option value="">{{ __('content.information.placeholders.district') }}</option>
                                    </select>
                                    @error('district')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="subdistrict" class="form-label">{{ __('content.information.fields.subdistrict') }}<span class="text-danger"> *</span></label>
                                    <select class="form-control @error('subdistrict') is-invalid @enderror" id="subdistrict" name="subdistrict" required disabled>
                                        <option value="">{{ __('content.information.placeholders.subdistrict') }}</option>
                                    </select>
                                    @error('subdistrict')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6">
                                    <label for="zipcode" class="form-label">{{ __('content.information.fields.zipcode') }}<span class="text-danger"> *</span></label>
                                    <input type="text" class="form-control @error('zipcode') is-invalid @enderror" id="zipcode" name="zipcode" value="{{ $fieldValue('zipcode') }}" placeholder="{{ __('content.information.placeholders.zipcode') }}"
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
                            <label>{{ __('content.information.fields.beneficiary') }} <span class="text-danger">{{ __('content.information.beneficiary_note') }}</span></label>
                            <input type="text" class="form-control @error('beneficiary') is-invalid @enderror" name="beneficiary" value="{{ $fieldValue('beneficiary') }}" placeholder="{{ __('content.information.placeholders.beneficiary') }}">
                            @error('beneficiary')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="information-section information-consent-section">
                            <h2>{{ __('content.information.consent_heading') }}</h2>
                            <div class="form-check">
                                <input class="form-check-input @error('personal_data_collection') is-invalid @enderror" type="checkbox" value="1" id="personal_data_collection" name="personal_data_collection" @checked($fieldValue('personal_data_collection')) required>
                                <label class="form-check-label" for="personal_data_collection">
                                    <span class="text-danger"> *</span>{{ __('content.information.consents.personal_data_collection') }}
                                </label>
                                @error('personal_data_collection')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('sensitive_personal_data') is-invalid @enderror" type="checkbox" value="1" id="sensitive_personal_data" name="sensitive_personal_data" @checked($fieldValue('sensitive_personal_data')) required>
                                <label class="form-check-label" for="sensitive_personal_data">
                                    <span class="text-danger"> *</span>{{ __('content.information.consents.sensitive_personal_data') }}
                                </label>
                                @error('sensitive_personal_data')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-check">
                                <input class="form-check-input @error('marketing_consent') is-invalid @enderror" type="checkbox" value="1" id="marketing_consent" name="marketing_consent" @checked($fieldValue('marketing_consent'))>
                                <label class="form-check-label" for="marketing_consent">
                                    {{ __('content.information.consents.marketing_consent') }}
                                </label>
                                @error('marketing_consent')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="health-question-actions">
                            <a href="{{ route('show-health-questions') }}" class="otp-btn otp-btn-outline">{{ __('content.otp.back') }}</a>
                            <button type="submit" class="check-premium-submit" id="informationSubmit">{{ __('content.check.continue') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('js')
<script>
    $(function () {
        const informationForm = $('#informationForm');
        const informationSubmit = $('#informationSubmit');
        let currentProvinceCode = '';
        const oldProvince = @json($fieldValue('province'));
        const oldDistrict = @json($fieldValue('district'));
        const oldSubdistrict = @json($fieldValue('subdistrict'));
        const oldZipcode = @json($fieldValue('zipcode'));
        const selectDistrictLabel = @json(__('content.information.placeholders.district'));
        const selectSubdistrictLabel = @json(__('content.information.placeholders.subdistrict'));
        const requiredFieldMessage = @json(__('content.information.validation.client_required'));

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
                const message = this.validationMessage || requiredFieldMessage;
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
                .html('<option value="">' + selectDistrictLabel + '</option>')
                .prop('disabled', true);
        }

        function resetSubdistrict() {
            $('#subdistrict')
                .html('<option value="">' + selectSubdistrictLabel + '</option>')
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
                province_code: provinceCode,
                language: "{{ $activeLang }}"
            });

            const districts = response.data || [];
            let options = '<option value="">' + selectDistrictLabel + '</option>';

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
                district_code: districtCode,
                language: "{{ $activeLang }}"
            });

            const subdistricts = response.data || [];
            let options = '<option value="">' + selectSubdistrictLabel + '</option>';

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
            $('#title_name').val(selectedTitle.val());
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
            $('#title_name').val(selectedTitle.val());
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
@endpush