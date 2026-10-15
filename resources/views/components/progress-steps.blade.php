@props(['active' => 'inquire'])

@php
    $steps = [
        'inquire' => [
            'label' => __('content.progress_steps.inquire'),
            'icon' => 'bi-pencil-square',
        ],
        'otp' => [
            'label' => __('content.progress_steps.otp'),
            'icon' => 'bi-mailbox-flag',
        ],
        'questionnaire' => [
            'label' => __('content.progress_steps.questionnaire'),
            'icon' => 'bi-ui-checks-grid',
        ],
        'information' => [
            'label' => __('content.progress_steps.information'),
            'icon' => 'bi-person-vcard',
        ],
        'review' => [
            'label' => __('content.progress_steps.review'),
            'icon' => 'bi-file-earmark-check',
        ],
        'payment' => [
            'label' => __('content.progress_steps.payment'),
            'icon' => 'bi-credit-card',
        ],
    ];

    $activeIndex = array_search($active, array_keys($steps), true);
    $activeIndex = $activeIndex === false ? 0 : $activeIndex;
@endphp

<ol class="mi-progress mi-progress-six" aria-label="{{ __('content.progress_steps.aria_label') }}">
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
