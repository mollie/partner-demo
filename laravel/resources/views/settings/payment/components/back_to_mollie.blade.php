<?php /** @var App\OnboardingStatus $status */ ?>

<a href="{{ $status->getDashboardLink() }}">
    <button class="btn-primary rounded btn-lg">{{ $buttonLabel }}</button>
</a>
