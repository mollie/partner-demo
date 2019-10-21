@extends('app', ['title' => __('Welcome'), 'active' => 'home'])

@section('dashboard_content')
    <h1>Welcome to Mollie</h1>
    <h5>
    Welcome to Mollie's Onboarding, {{ Auth::user()->company_name }}.<br/>
    Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essen
    </h5>
    <br/>
    <a href="settings/payment">
        <button class="btn-primary rounded btn-lg">Activate payments</button>
    </a>
@endsection
