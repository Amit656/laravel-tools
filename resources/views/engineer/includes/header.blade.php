<nav class="main-header navbar navbar-expand-md navbar-light navbar-white">
    <div class="container">
        <a href="{{ url('engineer/dashboard') }}" class="navbar-brand">
            <span class="brand-text font-weight-light">{{ config('app.name') }}</span>
        </a>
        <button class="navbar-toggler order-1" type="button" data-toggle="collapse" data-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse order-3" id="navbarCollapse">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a href="{{ url('engineer/request-tools') }}" class="nav-link {{ request()->is('engineer/request-tools') ? 'active' : '' }}">{{ __('common.request') }}&nbsp;{{ __('common.tools') }}</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('engineer/return-tools') }}" class="nav-link {{ request()->is('engineer/return-tools') ? 'active' : '' }}">{{ __('common.return') }}&nbsp;{{ __('common.tools') }}</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('engineer/history-request-tools') }}" class="nav-link {{ request()->is('engineer/history-request-tools') ? 'active' : '' }}">{{ __('common.request') }}&nbsp;{{ __('common.tools') }}&nbsp;{{ __('common.history') }}</a>
                </li>
                <li class="nav-item">
                    <a href="{{ url('engineer/history-return-tools') }}" class="nav-link {{ request()->is('engineer/history-return-tools') ? 'active' : '' }}">{{ __('common.return') }}&nbsp;{{ __('common.tools') }}&nbsp;{{ __('common.history') }}</a>
                </li>
            </ul>
        </div>
        <!-- Right navbar links -->
        <ul class="order-1 order-md-3 navbar-nav navbar-no-expand ml-auto">

            <!-- Notifications Dropdown Menu -->
            <!-- <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-warning navbar-badge">15</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                    <span class="dropdown-header">15 Notifications</span>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-envelope mr-2"></i> 4 new messages
                        <span class="float-right text-muted text-sm">3 mins</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-users mr-2"></i> 8 friend requests
                        <span class="float-right text-muted text-sm">12 hours</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item">
                        <i class="fas fa-file mr-2"></i> 3 new reports
                        <span class="float-right text-muted text-sm">2 days</span>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item dropdown-footer">See All Notifications</a>
                </div>
            </li> -->
            @if( auth()->user() )
              <li class="nav-item dropdown">
                  <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                        {{ Auth::user()->name }}
                </a>

                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item btn btn-default" href="{{ route('profile') }}">
                                        {{ __('common.profile') }}
                    </a>
                    <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                    </a>

                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                    </form>
                    
                </div>
              </li>
            @endif
        </ul>
    </div>
</nav>