@extends('layout.master')

@section('title', __('content.check_policy_form.title'))

@section('content')
<section id="policy-recheck-section" class="policy-recheck-section">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6 mx-auto">
                <form action="{{ route('check-policy') }}" method="get" class="check-premium-card policy-recheck-form">
                    <h1>{{ __('content.check_policy_form.title') }}</h1>
                    <p class="policy-recheck-subtitle">{{ __('content.check_policy_form.subtitle') }}</p>

                    <div class="policy-recheck-fields">
                        <div class="policy-recheck-row">
                            <label for="policy_number">{{ __('content.check_policy_form.policy_number') }}</label>
                            <input type="text" name="policy_number" id="policy_number" class="form-control" value="{{ request('policy_number') }}" required>
                        </div>

                        <div class="policy-recheck-row policy-recheck-id-row">
                            <div>
                                <label for="id_card_last_digits">{{ __('content.check_policy_form.id_number') }}</label>
                                <small>{{ __('content.check_policy_form.last_six_digits') }}</small>
                            </div>
                            <input type="text" name="id_card_last_digits" id="id_card_last_digits" class="form-control" maxlength="6" inputmode="numeric" pattern="[0-9]{6}" required>
                        </div>

                        <div class="policy-recheck-submit-row">
                            <button type="submit" class="policy-recheck-submit">{{ __('content.check_policy_form.submit') }}</button>
                        </div>
                    </div>

                    <p class="policy-recheck-note">{{ __('content.check_policy_form.note') }}</p>
                </form>
            </div>
        </div>
    </div>
</section>
@endsection
