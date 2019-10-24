@extends('app', ['title' => __('Settings') .' / ' . __('Payment'), 'active' => 'settings'])

@section('dashboard_content')

    <?php /** @var App\OnboardingStatus $status */ ?>
    <div class="text-content">
        @if($status->paymentsAreDisabledBecauseMollieNeedsMoreData())

            <h1>Get started with payments</h1>

            <p>
                Before you can accept live payments, Mollie will need some additional information. <br/>
                Please complete the required information and you will be ready to accept payments with Mollie.
            </p>

            @include('settings.payment.components.back_to_mollie', ['buttonLabel' => 'Add more information'])

        @elseif($status->settlementsAreDisabledBecauseMollieNeedsMoreData())

            <h1>Get started with payments</h1>

            <p>
                You are ready to start accepting your first payments! <br/>
                Before you can receive the payments on your bank account, Mollie will need a few more things.
            </p>

            @include('settings.payment.components.back_to_mollie', ['buttonLabel' => 'Add more information'])

        @elseif($status->settlementsAreDisabledBecauseMollieIsReviewing())

            <h1>Payments enabled</h1>

            <p>
                You are ready to start accepting your first payments! <br/>
                Mollie is reviewing your account for settlements, this usually takes 1-2 business days.
            </p>

            @include('settings.payment.components.back_to_mollie', ['buttonLabel' => 'Go to Mollie Dashboard'])

        @elseif($status->paymentsAndSettlementsAreDisabledBecauseMollieIsReviewing())

            <h1>Your account is being verified</h1>

            <p>
                You’ve supplied us with all information we need. We are now verifying your details. <br/>
                Our approval process usually takes 1 to 3 business days. In the meantime, you can continue setting up
                your integration with Mollie.
            </p>

            @include('settings.payment.components.back_to_mollie', ['buttonLabel' => 'Go to Mollie Dashboard'])

        @elseif($status->paymentsAndSettlementsAreEnabled())

            <h1>Payments enabled</h1>

            <p>
                You are ready to start accepting your first payments! <br/>
                Expect settlements on every Monday.
            </p>

            @include('components.back_to_mollie', ['buttonLabel' => 'Add more information'])

        @endif
    </div>
    @include('settings.payment.profiles')
@endsection
