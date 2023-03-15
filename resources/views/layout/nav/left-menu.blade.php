<div class="sticky">
    <div class="app-sidebar__overlay" data-bs-toggle="sidebar"></div>
    <div class="app-sidebar">
        <div class="side-header">
            <a class="header-brand1" href="/">
                <img src="{{ URL::asset('assets/images/brand/logo.png')}}" class="header-brand-img desktop-logo" alt="logo">
                <img src="{{ URL::asset('assets/images/brand/logo-1.png')}}" class="header-brand-img toggle-logo"
                     alt="logo">
                <img src="{{ URL::asset('assets/images/brand/logo-2.png')}}" class="header-brand-img light-logo" alt="logo">
                <img src="{{ URL::asset('assets/images/brand/logo-3.png')}}" class="header-brand-img light-logo1"
                     alt="logo">
            </a>
            <!-- LOGO -->
        </div>
        <div class="main-sidemenu">
            <div class="slide-left disabled" id="slide-left"><svg xmlns="http://www.w3.org/2000/svg"
                                                                  fill="#7b8191" width="24" height="24" viewBox="0 0 24 24">
                    <path d="M13.293 6.293 7.586 12l5.707 5.707 1.414-1.414L10.414 12l4.293-4.293z" />
                </svg></div>
            <ul class="side-menu">
                <li class="slide">
                    <a class="side-menu__item has-link" data-bs-toggle="slide" href="{{route('customer.list')}}"><i
                            class="side-menu__icon fe fe-users"></i><span
                            class="side-menu__label">Customers</span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item has-link" data-bs-toggle="slide" href="{{route('service.index')}}"><i
                            class="side-menu__icon fe fe-list"></i><span
                            class="side-menu__label">Services</span></a>
                </li>
                <li class="slide">
                    <a class="side-menu__item has-link" data-bs-toggle="slide" href="{{route('invoice.index')}}"><i
                            class="side-menu__icon fe fe-layers"></i><span
                            class="side-menu__label">Invoices</span></a>
                </li>
                @can('view-users-list')
                <li class="slide">
                    <a class="side-menu__item has-link" data-bs-toggle="slide" href="/users"><i
                            class="side-menu__icon fe fe-user"></i><span
                            class="side-menu__label">Employees</span></a>
                </li>
                @endcan
                <li class="slide">
                    <a class="side-menu__item has-link" data-bs-toggle="slide" href="/settings"><i
                            class="side-menu__icon fe fe-settings"></i><span
                            class="side-menu__label">Settings</span></a>
                </li>


            </ul>
            <div class="slide-right" id="slide-right"><svg xmlns="http://www.w3.org/2000/svg" fill="#7b8191"
                                                           width="24" height="24" viewBox="0 0 24 24">
                    <path d="M10.707 17.707 16.414 12l-5.707-5.707-1.414 1.414L13.586 12l-4.293 4.293z" />
                </svg></div>
        </div>
    </div>
    <!--/APP-SIDEBAR-->
</div>
