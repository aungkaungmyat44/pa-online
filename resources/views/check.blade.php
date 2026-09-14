@extends('layout.master')

@section('title', 'Check Premium')

@section('content')
<section id="check-premium-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <ol class="mi-progress mi-progress-six" aria-label="Check premium progress">
                    <li class="mi-progress-step is-active">
                        <span class="mi-progress-marker">
                            <i class="bi bi-pencil-square"></i>
                        </span>
                        <span class="mi-progress-label">Inquire</span>
                    </li>
                    <li class="mi-progress-step">
                        <span class="mi-progress-marker">
                            <i class="bi bi-mailbox-flag"></i>
                        </span>
                        <span class="mi-progress-label">OTP Verification</span>
                    </li>
                    <li class="mi-progress-step">
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
            <div class="col-lg-8 mx-auto">
                <div class="check-premium-card">
                    <h1>Please enter the following information to proceed</h1>
                    <form action="{{ route('otp-confirmation') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="occupation" class="form-label">Occupation</label>
                                <select class="form-control" id="occupation" name="occupation" required>
                                    <option value="">Select your occupation</option>
                                    <option value="Chief Executive Officer">Chief Executive Officer</option>
                                    <option value="Executive Vice President">Executive Vice President</option>
                                    <option value="President">President</option>
                                    <option value="Managing Director">Managing Director</option>
                                    <option value="Deputy Managing Director">Deputy Managing Director</option>
                                    <option value="Assistant Managing Director">Assistant Managing Director</option>
                                    <option value="Director">Director</option>
                                    <option value="Deputy Director">Deputy Director</option>
                                    <option value="Assistant Director">Assistant Director</option>
                                    <option value="Manager">Manager</option>
                                    <option value="Deputy Manager">Deputy Manager</option>
                                    <option value="Assistant Manager">Assistant Manager</option>
                                    <option value="General Officer">General Officer</option>
                                    <option value="Sale/Marketing Officer">Sale/Marketing Officer</option>
                                    <option value="Accounting Officer">Accounting Officer</option>
                                    <option value="Typist">Typist</option>
                                    <option value="Secretary">Secretary</option>
                                    <option value="Businessman">Businessman</option>
                                    <option value="Employee">Employee</option>
                                    <option value="Consultant">Consultant</option>
                                    <option value="Department Chief">Department Chief</option>
                                    <option value="Assistant Department Chief">Assistant Department Chief</option>
                                    <option value="Unit Chief">Unit Chief</option>
                                    <option value="Committee">Committee</option>
                                    <option value="Division Manager">Division Manager</option>
                                    <option value="Department Manager">Department Manager</option>
                                    <option value="Judge">Judge</option>
                                    <option value="Lawyer/Attorney">Lawyer/Attorney</option>
                                    <option value="Prosecutor">Prosecutor</option>
                                    <option value="Legal Consultant">Legal Consultant</option>
                                    <option value="Paralegal">Paralegal</option>
                                    <option value="Editor">Editor</option>
                                    <option value="Author">Author</option>
                                    <option value="Commentator">Commentator</option>
                                    <option value="Newscaster">Newscaster</option>
                                    <option value="Musician">Musician</option>
                                    <option value="Singer">Singer</option>
                                    <option value="Composer">Composer</option>
                                    <option value="Astronomer">Astronomer</option>
                                    <option value="Accountant">Accountant</option>
                                    <option value="Social worker">Social worker</option>
                                    <option value="Librarian">Librarian</option>
                                    <option value="Economist">Economist</option>
                                    <option value="Psychologist">Psychologist</option>
                                    <option value="Statistician">Statistician</option>
                                    <option value="Anthropologist">Anthropologist</option>
                                    <option value="Director-General">Director-General</option>
                                    <option value="Deputy Director-General">Deputy Director-General</option>
                                    <option value="Assistant Director-General">Assistant Director-General</option>
                                    <option value="Division Director">Division Director</option>
                                    <option value="Executive Government Officer">Executive Government Officer</option>
                                    <option value="Division Chief">Division Chief</option>
                                    <option value="Government Officer/State Enterprise">Government Officer/State Enterprise</option>
                                    <option value="Chief Executive">Chief Executive</option>
                                    <option value="Section Chief">Section Chief</option>
                                    <option value="Teacher">Teacher</option>
                                    <option value="Assistant Section Chief">Assistant Section Chief</option>
                                    <option value="Agricultural Cooperative Manager">Agricultural Cooperative Manager</option>
                                    <option value="Agricultural Cooperative Officer">Agricultural Cooperative Officer</option>
                                    <option value="Telephone/Telegraph Operator">Telephone/Telegraph Operator</option>
                                    <option value="Business Owner">Business Owner</option>
                                    <option value="Insurance Agent">Insurance Agent</option>
                                    <option value="Housekeeper">Housekeeper</option>
                                    <option value="Student">Student</option>
                                    <option value="Engineer">Engineer</option>
                                    <option value="Architect">Architect</option>
                                    <option value="Interior Designer">Interior Designer</option>
                                    <option value="Designer">Designer</option>
                                    <option value="Draftsman">Draftsman</option>
                                    <option value="Reporter">Reporter</option>
                                    <option value="Actor/Movie Star">Actor/Movie Star</option>
                                    <option value="Social Dancer">Social Dancer</option>
                                    <option value="Meteorologist">Meteorologist</option>
                                    <option value="Scientist">Scientist</option>
                                    <option value="Biologist">Biologist</option>
                                    <option value="Agronomist/Forester">Agronomist/Forester</option>
                                    <option value="Educator">Educator</option>
                                    <option value="Orchardist">Orchardist</option>
                                    <option value="Agriculturist">Agriculturist</option>
                                    <option value="Farmer">Farmer</option>
                                    <option value="Intelligence Officer">Intelligence Officer</option>
                                    <option value="Tailor">Tailor</option>
                                    <option value="Hairdresser">Hairdresser</option>
                                    <option value="Survent">Survent</option>
                                    <option value="Lanndryman">Lanndryman</option>
                                    <option value="Waiter">Waiter</option>
                                    <option value="Trader">Trader</option>
                                    <option value="Self employed">Self employed</option>
                                    <option value="Pedlar">Pedlar</option>
                                    <option value="Politician">Politician</option>
                                    <option value="Electrical Engineer">Electrical Engineer</option>
                                    <option value="Mechanical Engineer">Mechanical Engineer</option>
                                    <option value="Industrial Engineer">Industrial Engineer</option>
                                    <option value="Chemical Engineer">Chemical Engineer</option>
                                    <option value="Baker">Baker</option>
                                    <option value="Watchmaker">Watchmaker</option>
                                    <option value="Watch Repairer">Watch Repairer</option>
                                    <option value="Doctor/Physician">Doctor/Physician</option>
                                    <option value="Dentist">Dentist</option>
                                    <option value="Oculist">Oculist</option>
                                    <option value="Surgeon">Surgeon</option>
                                    <option value="Pharmacist">Pharmacist</option>
                                    <option value="Nurse">Nurse</option>
                                    <option value="Practical Nurse">Practical Nurse</option>
                                    <option value="Physical Therapist">Physical Therapist</option>
                                    <option value="X-Ray worker">X-Ray worker</option>
                                    <option value="Hospital worker">Hospital worker</option>
                                    <option value="Chemist">Chemist</option>
                                    <option value="Physicist">Physicist</option>
                                    <option value="Geologist">Geologist</option>
                                    <option value="Veterinarian">Veterinarian</option>
                                    <option value="Electrical Appliance Technician">Electrical Appliance Technician</option>
                                    <option value="Music Instrument Technician">Music Instrument Technician</option>
                                    <option value="Painter">Painter</option>
                                    <option value="Carpenter">Carpenter</option>
                                    <option value="Political Scientist">Political Scientist</option>
                                    <option value="Philosopher">Philosopher</option>
                                    <option value="Air Traffic Controller">Air Traffic Controller</option>
                                    <option value="Programmer">Programmer</option>
                                    <option value="System Analyst">System Analyst</option>
                                    <option value="Computer Engineer">Computer Engineer</option>
                                    <option value="Travel Consultant and Manager">Travel Consultant and Manager</option>
                                    <option value="Guide">Guide</option>
                                    <option value="Cook">Cook</option>
                                    <option value="Chef">Chef</option>
                                    <option value="Nutritionist">Nutritionist</option>
                                    <option value="Monk">Monk</option>
                                    <option value="Priest">Priest</option>
                                    <option value="Religious Occupations">Religious Occupations</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email address" required>
                            </div>
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">Date of Birth</label>
                                <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="Select your date of birth" autocomplete="off" required>
                            </div>
                            <div class="col-md-12">
                                Read <button type="button" class="terms-link" data-bs-toggle="modal" data-bs-target="#underwritingModal">terms and conditions</button> by Sahamongkhon.
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="check-premium-submit">Continue</button>
                            </div>
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
<script>
    $(function () {
        $('#occupation').select2({
            placeholder: 'Select your occupation',
            width: '100%'
        });

        $('#date_of_birth').datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: 'yy-mm-dd',
            maxDate: 0,
            yearRange: '-100:+0'
        });
    });
</script>
@endsection
