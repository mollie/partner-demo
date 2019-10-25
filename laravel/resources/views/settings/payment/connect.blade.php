@extends('app', ['title' => __('Settings') .' / ' . __('Payment'),  'active' => 'settings'])

@section('dashboard_content')
    <div class="text-content">
        <h1>Activate payments</h1>
        <p>
            We are using Mollie to accept payments. Take a minute to create an account with them and you'll be ready to go.
        </p>
        <br/>
        <a href="{{ $authLink }}">
            <img alt="" src="https://assets.docs.mollie.com/_images/button-small@2x.png" height="50">
        </a>
    </div>
@endsection
