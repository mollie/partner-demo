<!-- sidebar nav -->
<nav id="sidebar-nav">
    <ul class="nav flex-column nav-pills">
        <li class="nav-item"><a class="nav-link   {{ $active === 'home' ? 'active' : 'font-weight-bold'}}"  href="{{ route('home') }}">{{ __('Home') }}</a></li>
        <li class="nav-item"><a class="nav-link">Placeholder</a></li>
        <li class="nav-item"><a class="nav-link">Another placeholder</a></li>
        <li class="nav-item"><a class="nav-link">Last Placeholder</a></li>
        <li class="nav-item"><a class="nav-link {{ $active === 'settings' ? 'active' : 'font-weight-bold'}}" href="{{ route('payment_status') }}">{{ __('Settings') }}</a></li>
    </ul>
</nav>