@extends('layout.master')

@section('title', 'Information Form')

@section('content')
@php
    $customer = $customer ?? [];
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
                    <h1>Personal Information</h1>
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
                            <h2>Personal Info</h2>
                            <div class="row g-3">
                                <div class="col-md-4">
	                                    <label for="prefix" class="form-label">Prefix</label>
	                                    <select class="form-control" id="prefix" name="prefix" required>
	                                        <option value="">Select prefix</option>
	                                        @foreach (($nameTitles ?? []) as $nameTitle)
	                                            <option value="{{ $nameTitle->title }}">{{ $nameTitle->name_s }}</option>
	                                        @endforeach
	                                    </select>
	                                </div>
                                <div class="col-md-4">
                                    <label for="first_name" class="form-label">First Name</label>
                                    <input type="text" class="form-control" id="first_name" name="first_name" placeholder="Enter first name" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="last_name" class="form-label">Last Name</label>
                                    <input type="text" class="form-control" id="last_name" name="last_name" placeholder="Enter last name" required>
                                </div>
	                                <div class="col-md-4">
	                                    <label for="nationality" class="form-label">Nationality</label>
	                                    <select class="form-control" id="nationality" name="nationality" required>
	                                        <option value="">Select nationality</option>
	                                        @foreach (($countries ?? []) as $country)
	                                            <option value="{{ $country->ct_code }}">{{ ucwords(strtolower($country->ct_nameeng)) }}</option>
	                                        @endforeach
	                                    </select>
	                                </div>
                                <div class="col-md-4">
                                    <label for="identity_type" class="form-label">Identity Type</label>
                                    <select class="form-control" id="identity_type" name="identity_type" required>
                                        <option value="">Select identity type</option>
                                        @foreach (($cardTypes ?? []) as $cardType)
                                            <option value="{{ $cardType }}">{{ $cardType }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label for="identity_number" class="form-label">Identity Number</label>
                                    <input type="text" class="form-control" id="identity_number" name="identity_number" placeholder="Enter identity number" required>
                                </div>
                                <div class="col-md-4">
                                    <label for="date_of_birth" class="form-label">Date of Birth</label>
                                    <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" value="{{ $customer['date_of_birth'] ?? '' }}" readonly>
                                </div>
                            </div>
                        </div>

                        <div class="information-section">
                            <h2>Contact Info</h2>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="email" class="form-label">Email</label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ $customer['email'] ?? '' }}" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label for="phone_number" class="form-label">Phone Number</label>
                                    <input type="tel" class="form-control" id="phone_number" name="phone_number" placeholder="Enter phone number" required>
                                </div>
                            </div>
                        </div>

                        <div class="information-section">
                            <h2>Address Information</h2>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label for="full_address" class="form-label">Full Address</label>
                                    <textarea class="form-control" id="full_address" name="full_address" rows="3" placeholder="Enter full address" required></textarea>
                                </div>
                                <div class="col-md-6">
                                    <label for="province" class="form-label">Province</label>
                                    <select class="form-control" id="province" name="province" required>
                                        <option value="">Select province</option>
                                        @foreach (($provinces ?? []) as $province)
                                            <option value="{{ $province->province_code }}">{{ $province->province_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="district" class="form-label">District</label>
                                    <select class="form-control" id="district" name="district" required disabled>
                                        <option value="">Select district</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="subdistrict" class="form-label">Subdistrict</label>
                                    <select class="form-control" id="subdistrict" name="subdistrict" required disabled>
                                        <option value="">Select subdistrict</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <label for="zipcode" class="form-label">Zipcode</label>
                                    <input type="text" class="form-control" id="zipcode" name="zipcode" placeholder="Zipcode" required>
                                </div>
                            </div>
                        </div>

                        <div class="information-section">
                            <h2>Beneficiary</h2>
                            <input type="text" class="form-control" name="beneficiary" value="Legal heir" readonly>
                        </div>

                        <div class="information-section information-consent-section">
                            <h2>Consent</h2>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="personal_data_collection" name="personal_data_collection" required>
                                <label class="form-check-label" for="personal_data_collection">Personal Data Collection</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="sensitive_personal_data" name="sensitive_personal_data" required>
                                <label class="form-check-label" for="sensitive_personal_data">Sensitive Personal Data</label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="marketing_consent" name="marketing_consent">
                                <label class="form-check-label" for="marketing_consent">Marketing consent (optional)</label>
                            </div>
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
<script>
    $(function () {
        let currentProvinceCode = '';

        function resetDistrict() {
            $('#district')
                .html('<option value="">Select district</option>')
                .prop('disabled', true);
        }

        function resetSubdistrict() {
            $('#subdistrict')
                .html('<option value="">Select subdistrict</option>')
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
            let options = '<option value="">Select district</option>';

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
            let options = '<option value="">Select subdistrict</option>';

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
