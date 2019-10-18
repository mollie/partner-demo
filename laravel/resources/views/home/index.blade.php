@extends('layouts.card', ['title' => __('Welcome')])

@section('dashboard_content')
    Welcome, {{ Auth::user()->company_name }}
@endsection
