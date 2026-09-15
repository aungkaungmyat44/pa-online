@extends('layout.master')

@section('title', 'ตรวจสอบกรมธรรม์อีกครั้ง')

@section('content')
<section id="policy-recheck-section" class="policy-recheck-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mx-auto">
                <form action="{{ route('check-policy') }}" method="get" class="check-premium-card policy-recheck-form">
                    <h1>ตรวจสอบกรมธรรม์อีกครั้ง</h1>
                    <p class="policy-recheck-subtitle">กรุณากรอกข้อมูลของท่าน</p>

                    <div class="policy-recheck-fields">
                        <div class="policy-recheck-row">
                            <label for="policy_number">เลขที่กรมธรรม์</label>
                            <input type="text" name="policy_number" id="policy_number" class="form-control" required>
                        </div>

                        <div class="policy-recheck-row policy-recheck-id-row">
                            <div>
                                <label for="id_card_last_digits">เลขบัตรประชาชน</label>
                                <small>(6 หลักท้าย)</small>
                            </div>
                            <input type="text" name="id_card_last_digits" id="id_card_last_digits" class="form-control" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" required>
                        </div>

                        <div class="policy-recheck-submit-row">
                            <button type="submit" class="policy-recheck-submit">ค้นหากรมธรรม์</button>
                        </div>
                    </div>

                    <p class="policy-recheck-note">ระบบจะเรียกข้อมูลกรมธรรม์ในรูปแบบ PDF และสามารถดาวน์โหลดได้</p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
