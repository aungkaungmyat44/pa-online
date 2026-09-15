@extends('layout.master')

@section('title', 'คำนวณเบี้ยประกัน')

@section('content')
<section id="check-premium-section">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <x-progress-steps active="inquire" />
            </div>
        </div>
        <div class="row">
            <div class="col-lg-8 mx-auto">
                <div class="check-premium-card">
                    <h1>กรุณากรอกข้อมูลต่อไปนี้เพื่อดำเนินการต่อ</h1>
                    <form action="{{ route('otp-confirmation') }}" method="POST">
                        @csrf
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="occupation" class="form-label">อาชีพ</label>
                                <select class="form-control" id="occupation" name="occupation" required>
                                    <option value="">เลือกอาชีพของคุณ</option>
                                    @foreach ($occupations as $occupation)
                                        <option value="{{ $occupation }}">{{ $occupation }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="date_of_birth" class="form-label">วันเดือนปีเกิด</label>
                                <input type="text" class="form-control" id="date_of_birth" name="date_of_birth" placeholder="เลือกวันเดือนปีเกิด" autocomplete="off" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">อีเมล</label>
                                <input type="email" class="form-control" id="email" name="email" placeholder="กรอกอีเมลของคุณ" required>
                            </div>
                            <div class="col-md-12">
                                อ่าน <button type="button" class="terms-link" data-bs-toggle="modal" data-bs-target="#underwritingModal">ข้อกำหนดและเงื่อนไข</button> โดยสหมงคลประกันภัย
                            </div>
                            <div class="col-md-12">
                                <button type="submit" class="check-premium-submit">ดำเนินการต่อ</button>
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
                        รับจ้างทั่วไป,ผู้ปฏิบัติงานระเบิด/วัตถุระเบิด,ชาวประมง,พนักงานทำความสะอาดกระจก รวมถึงอาคารสูง,นักแข่งรถจักรยานยนต์,ช่างไฟฟ้าแรงสูง,พนักงานดับเพลิง,นักแสดงผาดโผนหรือสตั๊นท์แมน,นักแข่งรถ,นักมวย,นักประดาน้ำ,นักกีฬาเอ็กซ์ตรีม,นักกีฬาอาชีพ,นักปีนเขา,ผู้ปฏิบัติงานแท่นขุดเจาะ/แท่นผลิตกลางทะเล,คนงานแท่นขุดเจาะน้ำมัน,คนงานเหมืองใต้ดิน,คนงานเหมืองหิน,คนขับเรือ,เจ้าหน้าที่กู้ภัย,อาสาสมัครกู้ภัย,พนักงานติดตั้งเสาอากาศหรือป้ายโฆษณา,นักกีฬาต่อสู้/ศิลปะการต่อสู้อาชีพ,ทหาร/เจ้าหน้าที่ทหาร,ตำรวจ,นักบินและลูกเรือ,พยาบาล,แพทย์,ทันตแพทย์,จักษุแพทย์,ศัลยแพทย์,นักการเมือง,อาชีพอิสระ,ธุรกิจส่วนตัว,เจ้าของกิจการ,ค้าขาย,พนักงานX-RAY,พนักงานในโรงพยาบาล,พนักงานกายภาพบำบัด,ผู้ช่วยพยาบาล,ว่างงาน,นักเทคนิคการแพทย์,นักรังสีเทคนิค,ผู้ปฏิบัติงานเกี่ยวกับวัตถุระเบิด,คนงานรื้อถอนอาคาร,คนงานก่อสร้างบนที่สูง,ผู้ควบคุมปั้นจั่นหอสูง,ผู้ควบคุมเครื่องจักร/ปั้นจั่นหนัก,ช่างปฏิบัติงานสายไฟฟ้าแรงสูง,ผู้ปฏิบัติงานระบบไฟฟ้าแรงสูง,คนงานโรงงานเคมีที่มีความเสี่ยงสูง,ผู้ปฏิบัติงานกำจัดของเสียอันตราย,ผู้ฉีดพ่นสารกำจัดศัตรูพืช,ผู้ปฏิบัติงานกำจัดแมลง/สัตว์พาหะ,นักแสดงกายกรรม/ละครสัตว์,นักกายกรรม,นักกระโดดร่ม,นักร่มร่อน,นักบินเครื่องร่อน,นักปีนหน้าผาอาชีพ,คนงานก่อสร้างอุโมงค์ใต้ดิน,ช่างทำ/ซ่อมหลังคาที่ทำงานบนที่สูง,คนงานติดตั้ง/รื้อถอนนั่งร้าน,คนงานอู่ต่อเรือ
                    </p>
                </section>
            </div>
        </div>
    </div>
</div>
<script>
    $(function () {
        $('#occupation').select2({
            placeholder: 'เลือกอาชีพของคุณ',
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
