@extends('layout.master')

@section('title', 'Health Questions')

@section('content')
@php
    $customer = $customer ?? [];
    $questions = [
        'hospitalized' => 'Have you been hospitalized, had surgery, or received continuous medical treatment in the past 5 years?',
        'chronic_condition' => 'Have you ever been diagnosed with a chronic illness such as diabetes, heart disease, high blood pressure, stroke, cancer, kidney disease, or liver disease?',
        'disability' => 'Do you currently have any disability, loss of limb, paralysis, or physical impairment?',
        'accident_history' => 'Have you had a serious accident or injury that still affects your daily activities?',
        'dangerous_activity' => 'Do you regularly participate in hazardous sports or high-risk activities?',
        'medical_advice' => 'Are you currently waiting for medical test results, further investigation, surgery, or specialist consultation?',
    ];
@endphp

<section id="check-premium-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ol class="mi-progress mi-progress-six" aria-label="Check premium progress">
                    <li class="mi-progress-step is-done">
                        <span class="mi-progress-marker">
                            <i class="bi bi-pencil-square"></i>
                        </span>
                        <span class="mi-progress-label">Inquire</span>
                    </li>
                    <li class="mi-progress-step is-done">
                        <span class="mi-progress-marker">
                            <i class="bi bi-mailbox-flag"></i>
                        </span>
                        <span class="mi-progress-label">OTP Verification</span>
                    </li>
                    <li class="mi-progress-step is-active">
                        <span class="mi-progress-marker">
                            <i class="bi bi-ui-checks-grid"></i>
                        </span>
                        <span class="mi-progress-label">Questionnaire</span>
                    </li>
                    <li class="mi-progress-step">
                        <span class="mi-progress-marker">
                            <i class="bi bi-person-vcard"></i>
                        </span>
                        <span class="mi-progress-label">Personal Information</span>
                    </li>
                    <li class="mi-progress-step">
                        <span class="mi-progress-marker">
                            <i class="bi bi-file-earmark-check"></i>
                        </span>
                        <span class="mi-progress-label">Review</span>
                    </li>
                    <li class="mi-progress-step">
                        <span class="mi-progress-marker">
                            <i class="bi bi-credit-card"></i>
                        </span>
                        <span class="mi-progress-label">Payment</span>
                    </li>
                </ol>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-9 mx-auto">
                <div class="check-premium-card health-question-card">
                    <h1>Health Questionnaire</h1>
                    <form action="{{ route('information-form') }}" method="POST">
                        @csrf
                        <input type="hidden" name="occupation" value="{{ $customer['occupation'] ?? '' }}">
                        <input type="hidden" name="email" value="{{ $customer['email'] ?? '' }}">
                        <input type="hidden" name="date_of_birth" value="{{ $customer['date_of_birth'] ?? '' }}">
                        <input type="hidden" name="otp_code" value="{{ $customer['otp_code'] ?? '' }}">

                        <div class="health-plan-section">
                            <h2>Select Coverage Plan</h2>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <div class="health-plan-card">
                                        <h3>Plan 1</h3>
                                        <strong>200,000 Baht</strong>
                                        <ul>
                                            <li>Medical expenses up to 10,000 Baht</li>
                                            <li>Funeral expenses up to 10,000 Baht</li>
                                            <li>Standard room 300 Baht per day</li>
                                        </ul>
                                        <input class="btn-check" type="radio" name="selected_plan" id="plan_1" value="Plan 1" required>
                                        <label class="health-plan-button" for="plan_1">Choose Plan</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="health-plan-card">
                                        <h3>Plan 3</h3>
                                        <strong>800,000 Baht</strong>
                                        <ul>
                                            <li>Medical expenses up to 40,000 Baht</li>
                                            <li>Funeral expenses up to 15,000 Baht</li>
                                            <li>Standard room 800 Baht per day</li>
                                        </ul>
                                        <input class="btn-check" type="radio" name="selected_plan" id="plan_3" value="Plan 3" required>
                                        <label class="health-plan-button" for="plan_3">Choose Plan</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="health-plan-card">
                                        <h3>Plan 5</h3>
                                        <strong>1,500,000 Baht</strong>
                                        <ul>
                                            <li>Medical expenses up to 50,000 Baht</li>
                                            <li>Funeral expenses up to 20,000 Baht</li>
                                            <li>Standard room 1,000 Baht per day</li>
                                        </ul>
                                        <input class="btn-check" type="radio" name="selected_plan" id="plan_5" value="Plan 5" required>
                                        <label class="health-plan-button" for="plan_5">Choose Plan</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="health-question-list">
                            @foreach ($questions as $key => $question)
                                <div class="health-question-item">
                                    <p>{{ $loop->iteration }}. {{ $question }}</p>
                                    <div class="health-radio-group" role="radiogroup" aria-label="{{ $question }}">
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="health_questions[{{ $key }}]" id="{{ $key }}_no" value="No" required>
                                            <label class="form-check-label" for="{{ $key }}_no">No</label>
                                        </div>
                                        <div class="form-check form-check-inline">
                                            <input class="form-check-input" type="radio" name="health_questions[{{ $key }}]" id="{{ $key }}_yes" value="Yes" required>
                                            <label class="form-check-label" for="{{ $key }}_yes">Yes</label>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-check check-premium-terms mt-3">
                            <input class="form-check-input" type="checkbox" value="1" id="terms" name="terms" required>
                            <label class="form-check-label" for="terms">
                                I understand the <button type="button" class="terms-link" data-bs-toggle="modal" data-bs-target="#underwritingModal">terms and conditions</button> by Sahamongkhon.
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
                        Motorcycle taxi drivers, general laborers, construction workers, mechanics, miners, fishermen, high-rise window cleaners,
                        electricians, high-voltage electricians, surveyors, machinery repair technicians, security guards, firefighters, stunt performers,
                        bus drivers, taxi drivers, public transport and truck drivers, racing drivers, boxers, divers, extreme sports athletes,
                        professional athletes, mountaineers, mining workers, oil and natural gas rig workers, boat operators, rescue officers,
                        rescue volunteers, antenna or billboard installation workers, vocational engineering students, couriers, cash collection and delivery
                        personnel, factory workers, gas production workers, or any occupation involving a comparable level of risk, manual laborers or any
                        occupation involving a comparable level of risk, police officers, military personnel, pilots, and flight crew.
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
@endsection
