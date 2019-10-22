<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
@include('includes.head')
<body>
<div id="app">
    @include('includes.header')
    <div class='row'>
        @guest
            @yield('content')
        @else
            <div id="sidebar" class="py-3 col-md-2 d-md-block bg-light sidebar">
                @include('includes.sidebar', ['active' => $active ?? ''])
            </div>
            <main id="content" class="col-md pr-3">
                <div class="card-header d-none-sm bg-light">{{ $title ?? '' }}</div>
                <div class="dashboard_content pt-5 px-3 d-flex">
                    @yield('dashboard_content')
                </div>
            </main>
        @endif
    </div>
</div>
</body>
</html>
