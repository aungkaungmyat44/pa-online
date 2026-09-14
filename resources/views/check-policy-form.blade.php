@extends('layout.master')

@section('title', 'Policy Re-check')

@section('content')
<section id="policy-recheck-section" class="policy-recheck-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-7 mx-auto">
                <form action="{{ route('check-policy') }}" method="get" class="check-premium-card policy-recheck-form">
                    <h1>Policy re-check page</h1>
                    <p class="policy-recheck-subtitle">Please enter your information</p>

                    <div class="policy-recheck-fields">
                        <div class="policy-recheck-row">
                            <label for="policy_number">Policy Number</label>
                            <input type="text" name="policy_number" id="policy_number" class="form-control" required>
                        </div>

                        <div class="policy-recheck-row policy-recheck-id-row">
                            <div>
                                <label for="id_card_last_digits">ID Card No.</label>
                                <small>(Last 6 digits)</small>
                            </div>
                            <input type="text" name="id_card_last_digits" id="id_card_last_digits" class="form-control" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" required>
                        </div>

                        <div class="policy-recheck-submit-row">
                            <button type="submit" class="policy-recheck-submit">Search your policy</button>
                        </div>
                    </div>

                    <p class="policy-recheck-note">Call API to get the policy as PDF and be able to download</p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
