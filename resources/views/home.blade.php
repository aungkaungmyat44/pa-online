@extends('layout.master')

@section('title', 'Personal Accident Insurance')

@section('content')
<section id="banner-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mi-hero-banner" style="background-image:url('{{ asset('assets/images/banner-1.png') }}');"></div>
            </div>
        </div>
    </div>
</section>
<section id="explain-section" class="mt-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="explain-card">
                    <h2>What is Personal Insurance ?</h2>
                    <p>
                        Accidents can happen at any time &mdash; while driving, travelling, working, exercising, or simply going about your daily life.
                        Personal Accident (PA) Insurance provides financial protection when an unexpected accident results in injury, disability, or death.
                    </p>
                    <p>
                        Depending on the policy and coverage selected, PA Insurance may provide benefits for accidental medical expenses, permanent disability,
                        loss of certain limbs or bodily functions, and accidental death.
                    </p>
                    <p>
                        Unlike general health insurance, which is mainly designed to cover illnesses and medical conditions, Personal Accident Insurance focuses
                        specifically on injuries and losses caused by accidents.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="coverage-section" class="mt-4">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="coverage-heading">
                    <h2>Coverage Highlights</h2>
                </div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <h3>Comprehensive Protection for the Whole Family</h3>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <h3>Medical Expenses Up to 50,000 Baht per Accident</h3>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-hospital"></i>
                            </div>
                            <h3>Hospitalization &amp; ICU Compensation</h3>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <h3>Protection Wherever You Travel</h3>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-building-fill-check"></i>
                            </div>
                            <h3>Extensive Partner Hospital Network</h3>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <h3>76 Years of Experience You Can Trust</h3>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="premium-section" class="">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="premium-action">
                    <a href="{{ route('check-premium') }}" class="check-premium-btn">
                        <i class="bi bi-calculator"></i>
                        <span>Check Premium</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="plan-table-block">
                    <div class="section-heading">
                        <h2>Coverage Plans</h2>
                    </div>
                    <div class="plan-table-wrap">
                        <table class="plan-table">
                            <thead>
                                <tr>
                                    <th>
                                        <span>PA Next</span>
                                        <strong>Coverage</strong>
                                    </th>
                                    <th>
                                        <span>Plan 1</span>
                                        <strong>200,000</strong>
                                    </th>
                                    <th>
                                        <span>Plan 2</span>
                                        <strong>500,000</strong>
                                    </th>
                                    <th>
                                        <span>Plan 3</span>
                                        <strong>800,000</strong>
                                    </th>
                                    <th>
                                        <span>Plan 4</span>
                                        <strong>1,000,000</strong>
                                    </th>
                                    <th>
                                        <span>Plan 5</span>
                                        <strong>1,500,000</strong>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr class="plan-group">
                                    <td colspan="6">Death, Total and Permanent Disability, and Dismemberment</td>
                                </tr>
                                <tr>
                                    <td>- General Accidents (Excluding Motorcycle Riding or Traveling as a Passenger)</td>
                                    <td>200,000</td>
                                    <td>500,000</td>
                                    <td>800,000</td>
                                    <td>1,000,000</td>
                                    <td>1,500,000</td>
                                </tr>
                                <tr>
                                    <td>Being Murdered or Physically Assaulted</td>
                                    <td>200,000</td>
                                    <td>500,000</td>
                                    <td>800,000</td>
                                    <td>1,000,000</td>
                                    <td>1,500,000</td>
                                </tr>
                                <tr>
                                    <td>Riding or Traveling as a Passenger on a Motorcycle</td>
                                    <td>100,000</td>
                                    <td>250,000</td>
                                    <td>400,000</td>
                                    <td>500,000</td>
                                    <td>750,000</td>
                                </tr>
                                <tr>
                                    <td>Public Accidents (In Addition to General Accidents)</td>
                                    <td>200,000</td>
                                    <td>500,000</td>
                                    <td>800,000</td>
                                    <td>1,000,000</td>
                                    <td>1,500,000</td>
                                </tr>
                                <tr>
                                    <td>Funeral Expenses Due to an Accident</td>
                                    <td>10,000</td>
                                    <td>10,000</td>
                                    <td>15,000</td>
                                    <td>20,000</td>
                                    <td>20,000</td>
                                </tr>
                                <tr class="plan-group">
                                    <td colspan="6">Accident Hospitalization</td>
                                </tr>
                                <tr>
                                    <td>
                                        - Medical Expenses per Accident
                                        <span>(Unlimited Number of Accidents)</span>
                                    </td>
                                    <td>10,000</td>
                                    <td>25,000</td>
                                    <td>40,000</td>
                                    <td>50,000</td>
                                    <td>50,000</td>
                                </tr>
                                <tr>
                                    <td>
                                        Physical Therapy Costs
                                        <span>(Physical therapy costs resulting directly from the accident)</span>
                                    </td>
                                    <td colspan="5">Included in medical expenses per accident</td>
                                </tr>
                                <tr>
                                    <td>
                                        Post-Accident Mental Health Care Costs
                                        <span>(Psychological counseling fees resulting directly from the accident)</span>
                                    </td>
                                    <td colspan="5">Included in medical expenses per accident</td>
                                </tr>
                                <tr>
                                    <td>- Compensation for bone fractures, burns, scalds, and internal organ injuries</td>
                                    <td>10,000</td>
                                    <td>10,000</td>
                                    <td>15,000</td>
                                    <td>20,000</td>
                                    <td>20,000</td>
                                </tr>
                                <tr>
                                    <td>- Dental Expenses Due to an Accident</td>
                                    <td>2,000</td>
                                    <td>2,000</td>
                                    <td>3,000</td>
                                    <td>4,000</td>
                                    <td>4,000</td>
                                </tr>
                                <tr class="plan-group">
                                    <td colspan="6">Income Compensation Due to an Accident</td>
                                </tr>
                                <tr>
                                    <td>- Standard Room <span>(Maximum 30 Days per Accident)</span></td>
                                    <td>300</td>
                                    <td>500</td>
                                    <td>800</td>
                                    <td>1,000</td>
                                    <td>1,000</td>
                                </tr>
                                <tr>
                                    <td>- ICU Room <span>(Maximum 15 Days per Accident)</span></td>
                                    <td>600</td>
                                    <td>1,000</td>
                                    <td>1,600</td>
                                    <td>2,000</td>
                                    <td>2,000</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="faq-section" class="faq-section mt-4">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="faq-heading">
                    <h2>Frequently Asked Questions</h2>
                </div>

                <div class="faq-list">
                    <div class="faq-item">
                        <h3>What is Personal Accident Insurance?</h3>
                        <p>It provides financial protection if an accident causes injury, disability, or death.</p>
                    </div>
                    <div class="faq-item">
                        <h3>Who can apply?</h3>
                        <p>Coverage is available for eligible applicants aged 1 to 75 years, subject to underwriting conditions.</p>
                    </div>
                    <div class="faq-item">
                        <h3>Does it cover medical expenses?</h3>
                        <p>Yes. Medical expenses are covered according to the selected plan and policy conditions.</p>
                    </div>
                    <div class="faq-item">
                        <h3>Is motorcycle riding covered?</h3>
                        <p>Motorcycle-related coverage depends on the selected plan and policy extension conditions.</p>
                    </div>
                    <div class="faq-item">
                        <h3>Can I buy more than one policy?</h3>
                        <p>Each insured person may hold a maximum of one policy under this insurance scheme.</p>
                    </div>
                    <div class="faq-item">
                        <h3>How long is the policy period?</h3>
                        <p>This is an annual Personal Accident Insurance policy.</p>
                    </div>
                    <div class="faq-item">
                        <h3>How do I receive my policy?</h3>
                        <p>You can receive the policy by email or request delivery by mail within 15 days.</p>
                    </div>
                    <div class="faq-item">
                        <h3>How can I check my policy?</h3>
                        <p>Use the Check Policy menu and enter your policy number with the last 6 digits of your ID card.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="customer-feedback-section" class="customer-feedback-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                <div class="section-heading">
                    <h2>Customer Feedbacks</h2>
                </div>

                <div id="customerFeedbackCarousel" class="carousel slide feedback-carousel" data-bs-ride="carousel">
                    <div class="carousel-indicators feedback-carousel-dots">
                        <button type="button" data-bs-target="#customerFeedbackCarousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Feedback slide 1"></button>
                        <button type="button" data-bs-target="#customerFeedbackCarousel" data-bs-slide-to="1" aria-label="Feedback slide 2"></button>
                    </div>

                    <div class="carousel-inner">
                        <div class="carousel-item active">
                            <div class="row g-3">
                                <div class="col-lg-3 col-md-6">
                                    <div class="feedback-card">
                                        <div class="feedback-avatar">MT</div>
                                        <div class="feedback-stars">★★★★★</div>
                                        <p>Buying the policy was quick and easy. The steps were clear from start to finish.</p>
                                        <strong>May Thandar</strong>
                                        <span>Office Employee</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="feedback-card">
                                        <div class="feedback-avatar">NS</div>
                                        <div class="feedback-stars">★★★★★</div>
                                        <p>The plan details were simple to compare, and I received my policy by email.</p>
                                        <strong>Nattapong S.</strong>
                                        <span>Business Owner</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="feedback-card">
                                        <div class="feedback-avatar">EM</div>
                                        <div class="feedback-stars">★★★★☆</div>
                                        <p>The online form was convenient. I liked being able to review everything before payment.</p>
                                        <strong>Ei Mon</strong>
                                        <span>Teacher</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="feedback-card">
                                        <div class="feedback-avatar">TP</div>
                                        <div class="feedback-stars">★★★★★</div>
                                        <p>Good coverage options for the family and a smooth purchase experience.</p>
                                        <strong>Thanakorn P.</strong>
                                        <span>Engineer</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="carousel-item">
                            <div class="row g-3">
                                <div class="col-lg-3 col-md-6">
                                    <div class="feedback-card">
                                        <div class="feedback-avatar">SH</div>
                                        <div class="feedback-stars">★★★★★</div>
                                        <p>The process felt secure, and the payment page was easy to understand.</p>
                                        <strong>Su Su Hlaing</strong>
                                        <span>Consultant</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="feedback-card">
                                        <div class="feedback-avatar">WK</div>
                                        <div class="feedback-stars">★★★★☆</div>
                                        <p>I could check my policy afterward, which made the service feel reliable.</p>
                                        <strong>Worawit K.</strong>
                                        <span>Manager</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="feedback-card">
                                        <div class="feedback-avatar">MT</div>
                                        <div class="feedback-stars">★★★★★</div>
                                        <p>Clear protection benefits and simple documents. Very useful for daily travel.</p>
                                        <strong>May Thandar</strong>
                                        <span>Office Employee</span>
                                    </div>
                                </div>
                                <div class="col-lg-3 col-md-6">
                                    <div class="feedback-card">
                                        <div class="feedback-avatar">NS</div>
                                        <div class="feedback-stars">★★★★★</div>
                                        <p>The policy delivery options were helpful and easy to choose.</p>
                                        <strong>Nattapong S.</strong>
                                        <span>Business Owner</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

@endsection
