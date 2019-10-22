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

            <a href="{{ $status->getDashboardLink() }}">
                <button class="btn-primary rounded btn-lg">Add more information</button>
            </a>

        @elseif($status->settlementsAreDisabledBecauseMollieNeedsMoreData())

            <h1>Get started with payments</h1>

            <p>
                You are ready to start accepting your first payments! <br/>
                Before you can receive the payments on your bank account, Mollie will need a few more things.
            </p>

            <a href="{{ $status->getDashboardLink() }}">
                <button class="btn-primary rounded btn-lg">Add more information</button>
            </a>

        @elseif($status->settlementsAreDisabledBecauseMollieIsReviewing())

            <h1>Payments enabled</h1>

            <p>
                You are ready to start accepting your first payments! <br/>
                Mollie is reviewing your account for settlements, this usually takes 1-2 business days.
            </p>

        @elseif($status->paymentsAndSettlementsAreDisabledBecauseMollieIsReviewing())

            <h1>Your account is being verified</h1>

            <p>
                You’ve supplied us with all information we need. We are now verifying your details. <br/>
                Our approval process usually takes 1 to 3 business days. In the meantime, you can continue setting up
                your
                integration with Mollie.
            </p>

        @elseif($status->paymentsAndSettlementsAreEnabled())

            <h1>Payments enabled</h1>

            <p>
                You are ready to start accepting your first payments! <br/>
                Expect settlements on every Monday.
            </p>

            <a href="{{ $status->getDashboardLink() }}">
                <button class="btn-primary rounded btn-lg">Add more information</button>
            </a>

        @endif
    </div>
    <div class="payment-methods">
        <h4 class="font-weight-bold ml-4">Active Payment Methods</h4>
        <ul>
            <li>
                <div>
                    <span class="status status-green"></span>
                    ideka
                </div>
            </li>
        </ul>
        <h4 class="font-weight-bold ml-4">Pending Payment Methods</h4>
        <ul>
            <li>
                <div>
                    <span class="status status-yellow"></span>
                    creditcradd
                </div>
            </li>
        </ul>
    </div>
@endsection
