<nav class="navbar navbar-expand-md navbar-dark bg-blue shadow-sm">
        <a class="navbar-brand" href="{{ url('/') }}">
            <img src="/images/logo.png" class="logo"/>
        </a>
            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto flex-row">
                <li class="nav-item flex-center">
                    <a href="https://docs.mollie.com/reference/v2/payments-api/create-payment" target="_blank">
                        <img src="/images/docs.svg" class="nav-link"/>
                    </a>
                </li>
                <li class="nav-item flex-center">
                    <a href="https://github.com/mollie/partner-demo" target="_blank">
                        <img src="/images/github.svg" class="nav-link"/>
                    </a>
                </li>
                <!-- Authentication Links -->
                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('register') }}">{{ __('Register') }}</a>
                    </li>
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                           data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->company_name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ route('logout') }}"
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                  style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                @endguest
            </ul>
</nav>