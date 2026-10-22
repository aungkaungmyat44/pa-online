@extends('layout.master')

@section('title', __('content.title'))

@section('content')
<section id="banner-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mi-hero-banner" style="background-image:url('{{ asset('assets/images/'.__('content.banner')) }}');"></div>
            </div>
        </div>
    </div>
</section>
<section id="explain-section" class="mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="explain-card">
                    <h2>{{ __('content.explanation_heading') }}</h2>
                    @foreach (__('content.explanation_paragraphs') as $paragraph)
                        <p>{{ $paragraph }}</p>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>
<section id="coverage-section" class="mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="coverage-heading">
                    <h2>{{ __('content.coverage_highlights_heading') }}</h2>
                </div>
                <div class="row g-4">
                    @foreach ([
                        'family' => 'people-fill',
                        'medical' => 'cash-coin',
                        'hospital' => 'hospital',
                        'travel' => 'geo-alt-fill',
                        'network' => 'building-fill-check',
                        'experience' => 'award-fill',
                    ] as $key => $icon)
                        <div class="col-md-6 col-lg-4">
                            <div class="coverage-card">
                                <div class="coverage-icon">
                                    <i class="bi bi-{{ $icon }}"></i>
                                </div>
                                <p>{{ __('content.coverage_highlights.'.$key) }}</p>
                            </div>
                        </div>
                    @endforeach
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
                        <span>{{ __('content.check_premium') }}</span>
                    </a>
                    <a href="{{ asset('assets/files/policy_2.pdf') }}" class="check-premium-btn policy-download-btn" download>
                        <i class="bi bi-download"></i>
                        <span>{{ __('content.policy_terms') }}</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="faq-item objectives-card">
                    <h6>{{ __('content.sales_objective_heading') }}</h6>
                    <p>{{ __('content.sales_objective') }}</p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="plan-table-block">
                    <div class="section-heading">
                        <h2>{{ __('content.coverage_table_heading') }}</h2>
                    </div>
                    <div class="plan-table-wrap">
                        <table class="plan-table">
                            <thead>
                                <tr>
                                    <th>
                                        <p>{{ __('content.coverage_column_heading') }}</p>
                                    </th>
                                    @foreach ($coveragePlans as $plan)
                                        <th>
                                            <p>{{ $plan['title'] }}</p>
                                        </th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($coverageRows as $row)
                                    <tr>
                                        <td>{{ $row['label'] }}</td>
                                        @foreach ($row['amounts'] as $amount)
                                            <td>{{ $amount }}</td>
                                        @endforeach
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <ol class="insurance-conditions mt-3">
                        @foreach (__('content.insurance_conditions') as $condition)
                            <li>{{ $condition }}</li>
                        @endforeach
                    </ol>
                    <div class="alert alert-danger" role="alert">
                        {{ __('content.regulatory_warning') }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<section id="faq-section" class="faq-section my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-12">
                <div class="faq-heading">
                    <h2>{{ __('content.faq_heading') }}</h2>
                </div>

                <div class="faq-list">
                    @foreach (__('content.faqs') as $faq)
                        <div class="faq-item">
                            <h6>{{ $faq['question'] }}</h6>
                            <p>{{ $faq['answer'] }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>

{{-- <section id="customer-feedback-section" class="customer-feedback-section">
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
</section> --}}
@endsection
