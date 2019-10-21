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
            <div id="sidebar" class="py-3 px-4 col-md-2 d-none d-md-block bg-light sidebar">
                @include('includes.sidebar', ['active' => $active])
            </div>
            <main id="content" class="col-md pr-3">
                <div class="card-header bg-light">{{ $title }}</div>
                <div class="pt-5">
                    @yield('dashboard_content')
                </div>
            </main>
        @endif
    </div>
</div>
</body>
</html>
