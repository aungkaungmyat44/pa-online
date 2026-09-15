@props(['active' => 'inquire'])

@php
    $steps = [
        'inquire' => [
            'label' => 'สอบถามข้อมูล',
            'icon' => 'bi-pencil-square',
        ],
        'otp' => [
            'label' => 'ยืนยัน OTP',
            'icon' => 'bi-mailbox-flag',
        ],
        'questionnaire' => [
            'label' => 'แบบสอบถาม',
            'icon' => 'bi-ui-checks-grid',
        ],
        'information' => [
            'label' => 'ข้อมูลส่วนตัว',
            'icon' => 'bi-person-vcard',
        ],
        'review' => [
            'label' => 'ตรวจสอบ',
            'icon' => 'bi-file-earmark-check',
        ],
        'payment' => [
            'label' => 'ชำระเงิน',
            'icon' => 'bi-credit-card',
        ],
    ];

    $activeIndex = array_search($active, array_keys($steps), true);
    $activeIndex = $activeIndex === false ? 0 : $activeIndex;
@endphp

<ol class="mi-progress mi-progress-six" aria-label="ขั้นตอนการคำนวณเบี้ยประกัน">
    @foreach ($steps as $stepKey => $step)
        @php
            $stepIndex = $loop->index;
            $stepClass = $stepIndex < $activeIndex ? 'is-done' : ($stepKey === $active ? 'is-active' : '');
        @endphp
        <li class="mi-progress-step {{ $stepClass }}">
            <span class="mi-progress-marker">
                <i class="bi {{ $step['icon'] }}"></i>
            </span>
            <span class="mi-progress-label">{{ $step['label'] }}</span>
        </li>
    @endforeach
</ol>
