@extends('layouts.card', ['title' => __('Settings') .' / ' . __('Payment')])

@section('dashboard_content')
    <div>
        <a href="{{ $authLink }}">
            <img alt="" src="https://assets.docs.mollie.com/_images/button-small@2x.png" height="50">
        </a>
    </div>
@endsection
