<nav class="navbar navbar-expand-md navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="{{ routeTenant('home') }}">
            {{ config('app.name', 'Laravel') }}
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <!-- Left Side Of Navbar -->
            <ul class="navbar-nav mr-auto">
                @auth
                <li class="nav-item">
                    <a class="nav-link" href="{{ routeTenant('bank_accounts.index') }}">C/C</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ routeTenant('budget_financial.index') }}">Orçamento Financeiro</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ routeTenant('expense.index') }}">Despesas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ routeTenant('income.index') }}">Receitas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ routeTenant('cred_card.index') }}">Cartões de Crédito</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="{{ routeTenant('car.index') }}">Carros</a>
                </li>
                @endauth
            </ul>

            <!-- Right Side Of Navbar -->
            <ul class="navbar-nav ml-auto">
                <!-- Authentication Links -->
                @guest
                <li class="nav-item">
                    <a class="nav-link" href="{{ routeTenant('login') }}">{{ __('Login') }}</a>
                </li>
                @if (Route::has('register'))
                    <li class="nav-item">
                        <a class="nav-link" href="{{ routeTenant('register') }}">{{ __('Register') }}</a>
                    </li>
                @endif
                @else
                    <li class="nav-item dropdown">
                        <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                            {{ Auth::user()->name }} <span class="caret"></span>
                        </a>

                        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                            <a class="dropdown-item" href="{{ routeTenant('logout') }}"
                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>

                            <form id="logout-form" action="{{ routeTenant('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>

                    @endguest
            </ul>
        </div>
    </div>
</nav>