@extends('layout.master')

@section('title', 'Personal Accident Insurance')

@section('content')
<section id="banner-section">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="mi-hero-banner" style="background-image:url('{{ asset('assets/images/banner_th.png') }}');"></div>
            </div>
        </div>
    </div>
</section>
<section id="explain-section" class="mt-5">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="explain-card">
                    <h2>ประกันภัยอุบัติเหตุส่วนบุคคลคืออะไร ?</h2>
                    <p>
                        อุบัติเหตุสามารถเกิดขึ้นได้ทุกเวลา ไม่ว่าจะเป็นระหว่างขับรถ เดินทาง ทำงาน ออกกำลังกาย หรือใช้ชีวิตประจำวัน
                        ประกันภัยอุบัติเหตุส่วนบุคคล (PA) ช่วยคุ้มครองทางการเงินเมื่อเกิดอุบัติเหตุที่ไม่คาดคิดจนทำให้บาดเจ็บ ทุพพลภาพ หรือเสียชีวิต
                    </p>
                    <p>
                        ความคุ้มครองและผลประโยชน์จะขึ้นอยู่กับแผนประกันภัยที่เลือก เช่น ค่ารักษาพยาบาลจากอุบัติเหตุ การทุพพลภาพถาวร
                        การสูญเสียอวัยวะหรือสมรรถภาพของร่างกายบางส่วน และการเสียชีวิตจากอุบัติเหตุ
                    </p>
                    <p>
                        แตกต่างจากประกันสุขภาพทั่วไปที่เน้นคุ้มครองการเจ็บป่วยและโรคต่าง ๆ ประกันภัยอุบัติเหตุส่วนบุคคลจะเน้นคุ้มครอง
                        การบาดเจ็บและความสูญเสียที่เกิดจากอุบัติเหตุโดยเฉพาะ
                    </p>
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
                    <h2>จุดเด่นความคุ้มครอง</h2>
                </div>
                <div class="row g-4">
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-people-fill"></i>
                            </div>
                            <p>คุ้มครองครอบคลุมสำหรับทั้งครอบครัว</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-cash-coin"></i>
                            </div>
                            <p>ค่ารักษาพยาบาลสูงสุด 30,000 บาทต่ออุบัติเหตุ</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-hospital"></i>
                            </div>
                            <p>เงินชดเชยกรณีนอนโรงพยาบาลและห้อง ICU</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-geo-alt-fill"></i>
                            </div>
                            <p>คุ้มครองทุกที่ที่คุณเดินทาง</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-building-fill-check"></i>
                            </div>
                            <p>เครือข่ายโรงพยาบาลคู่สัญญาครอบคลุม</p>
                        </div>
                    </div>
                    <div class="col-md-6 col-lg-4">
                        <div class="coverage-card">
                            <div class="coverage-icon">
                                <i class="bi bi-award-fill"></i>
                            </div>
                            <p>ประสบการณ์กว่า 76 ปีที่คุณไว้วางใจได้</p>
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
                        <span>คำนวณเบี้ย</span>
                    </a>
                    <a href="{{ asset('assets/files/policy.pdf') }}" class="check-premium-btn policy-download-btn" download>
                        <i class="bi bi-download"></i>
                        <span>ดาวน์โหลดกรมธรรม์</span>
                    </a>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="faq-item objectives-card">
                    <h6>วัตถุประสงค์แจ้งการขายประกันอุบัติเหตุส่วนบุคคล</h6>
                    <p>
                        กรมธรรม์อุบัติเหตุส่วนบุคคลนี้ให้คุ้มครองความเสี่ยงจากอุบัติเหตุ รวมถึงค่ารักษาพยาบาล
                        และค่าเบี้ยประกันภัยไม่แพงเมื่อเทียบกับวงเงินคุ้มครอง เหมาะสำหรับบุคคลปกติทั่วไป
                        ที่มีอายุตั้งแต่ 16 – 60 ปี ทั้งนี้ เป็นการลดภาระทางการเงิน โดยเฉพาะผู้ที่เดินทางบ่อย
                        ทำงานที่มีความเสี่ยง หรือเป็นผู้หารายได้หลักของครอบครัว
                    </p>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="plan-table-block">
                    <div class="section-heading">
                        <h2>แผนความคุ้มครอง</h2>
                    </div>
                    <div class="plan-table-wrap">
                        <table class="plan-table">
                            <thead>
                                <tr>
                                    <th>
                                        <p>ความคุ้มครอง</p>
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
                        <li>เงื่อนไขของการประกันภัย</li>
                        <li>ข้าพเจ้ายอมรับเงื่อนไขการประกันภัย</li>
                        <li>ข้าพเจ้ามีความประสงค์ขอเอาประกันภัยกับบริษัทตามเงื่อนไขของกรมธรรม์ประกันภัยที่บริษัทได้ใช้สำหรับการประกันภัยนี้และข้าพเจ้ารับรองว่า รายละเอียดต่างๆที่ข้าพเจ้าแถลงข้างต้นนี้มีความถูกต้องและสมบูรณ์</li>
                        <li>ข้าพเจ้าตกลงที่จะให้คำขอเอาประกันภัยนี้เป็นมูลฐานสัญญาประกันภัยระหว่างข้าพเจ้าและบริษัทหากรายละเอียดของข้าพเจ้าเป็นเท็จหรือปกปิดไม่แจ้งความจริง ข้าพเจ้ายินยอมให้บริษัทฯ บอกเลิกสัญญาประกันภัยได้</li>
                        <li>ข้าพเจ้ายินยอมให้บริษัทฯ จัดเก็บ ใช้ และเปิดเผย ข้อเท็จจริงเกี่ยวกับสุขภาพและข้อมูลของข้าพเจ้าต่อสำนักงานคณะกรรมการกำกับและส่งเสริมการประกอบธุรกิจประกันภัย เพื่อประโยชน์ในการกำกับดูแลธุรกิจประกันภัย</li>
                    </ol>
                    <div class="alert alert-danger" role="alert">
                        คำเตือนของสำนักงานคณะกรรมการกำกับและส่งเสริมการประกอบธุรกิจประกันภัย (คปภ.) การปกปิดข้อเท็จจริงใดๆหรือการแถลงข้อความอันเป็นเท็จจะมีผลให้สัญญาประกันภัยนี้ต้องเป็นโมฆียะอาจจะเป็นเหตุให้บริษัทผู้รับประกันภัย ปฏิเสธความรับผิดตามสัญญาประกันภัยหรือบอกล้างสัญญาประกันภัยได้ ตามประมวลกฎหมายแพ่งและพาณิชย์มาตรา 865
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
                    <h2>คำถามที่พบบ่อย</h2>
                </div>

                <div class="faq-list">
                    <div class="faq-item">
                        <h6>ประกันภัยอุบัติเหตุส่วนบุคคลคืออะไร?</h6>
                        <p>เป็นประกันภัยที่ช่วยคุ้มครองทางการเงิน หากอุบัติเหตุทำให้เกิดการบาดเจ็บ ทุพพลภาพ หรือเสียชีวิต</p>
                    </div>
                    <div class="faq-item">
                        <h6>ใครสามารถสมัครได้บ้าง?</h6>
                        <p>ผู้สมัครที่มีคุณสมบัติตามเงื่อนไขและมีอายุตั้งแต่ 1 ถึง 60 ปี สามารถสมัครได้ ทั้งนี้ขึ้นอยู่กับเงื่อนไขการพิจารณารับประกันภัย</p>
                    </div>
                    <div class="faq-item">
                        <h6>คุ้มครองค่ารักษาพยาบาลหรือไม่?</h6>
                        <p>คุ้มครองค่ารักษาพยาบาลตามแผนประกันภัยที่เลือกและเงื่อนไขของกรมธรรม์</p>
                    </div>
                    <div class="faq-item">
                        <h6>คุ้มครองการขับขี่หรือโดยสารรถจักรยานยนต์หรือไม่?</h6>
                        <p>ความคุ้มครองเกี่ยวกับรถจักรยานยนต์ขึ้นอยู่กับแผนประกันภัยที่เลือกและเงื่อนไขความคุ้มครองเพิ่มเติม</p>
                    </div>
                    <div class="faq-item">
                        <h6>สามารถซื้อได้มากกว่าหนึ่งกรมธรรม์หรือไม่?</h6>
                        <p>ผู้เอาประกันภัยแต่ละรายสามารถถือกรมธรรม์ภายใต้โครงการนี้ได้สูงสุดหนึ่งกรมธรรม์</p>
                    </div>
                    <div class="faq-item">
                        <h6>ระยะเวลาคุ้มครองนานเท่าไร?</h6>
                        <p>กรมธรรม์ประกันภัยอุบัติเหตุส่วนบุคคลนี้มีระยะเวลาคุ้มครอง 1 ปี</p>
                    </div>
                    <div class="faq-item">
                        <h6>จะได้รับกรมธรรม์อย่างไร?</h6>
                        <p>สามารถรับกรมธรรม์ทางอีเมล หรือเลือกให้จัดส่งทางไปรษณีย์ภายใน 15 วัน</p>
                    </div>
                    <div class="faq-item">
                        <h6>สามารถตรวจสอบกรมธรรม์ได้อย่างไร?</h6>
                        <p>ใช้เมนูเช็คกรมธรรม์ แล้วกรอกเลขที่กรมธรรม์พร้อมเลขบัตรประชาชน 6 หลักสุดท้าย</p>
                    </div>
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
