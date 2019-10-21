@extends('app', ['title' => __('Home'), 'active' => 'home'])

@section('dashboard_content')
    <h1>Hello, {{ Auth::user()->company_name }}!</h1>
    <p>
        Welcome to <i>Amazing Platform Demo App.</i></p>
    <p>
        This is a dummy platform to be used as an example of the Mollie's Hosted Onboarding.
        To test the example functionality, press the button below or navigate to <strong>Settings</strong> through the sidebar.
    </p>
    <br/>
    <a href="settings/payment">
        <button class="btn-primary rounded btn-lg">Payments setup</button>
    </a>
@endsection
