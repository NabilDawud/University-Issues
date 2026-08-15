<!doctype html>
<html lang="en">
<!--begin::Head-->

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>{{ config('app.name') }} | @yield('title', 'Dashboard')</title>

    <!--begin::Theme Init (prevents flash of incorrect theme on load, #6043)-->
    <script>
        (() => {
            'use strict';
            const STORAGE_KEY = 'lte-theme';
            let stored = null;
            try {
                stored = localStorage.getItem(STORAGE_KEY);
            } catch {
                // localStorage may be unavailable (private mode, sandboxed iframe).
            }
            const prefersDark = globalThis.matchMedia('(prefers-color-scheme: dark)').matches;
            // Mirror the resolution in _scripts.astro: explicit "dark"/"light" win,
            // otherwise ("auto" or unset) fall back to the OS preference.
            let resolved = 'light';
            if (stored === 'dark' || stored === 'light') {
                resolved = stored;
            } else if (prefersDark) {
                resolved = 'dark';
            }
            document.documentElement.setAttribute('data-bs-theme', resolved);
            document.documentElement.style.colorScheme = resolved;
        })();
    </script>
    <!--end::Theme Init-->

    <!--begin::Accessibility Meta Tags-->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="color-scheme" content="light dark" />
    <meta name="theme-color" content="#007bff" media="(prefers-color-scheme: light)" />
    <meta name="theme-color" content="#1a1a1a" media="(prefers-color-scheme: dark)" />
    <!--end::Accessibility Meta Tags-->

    <!--begin::Primary Meta Tags-->
    <meta name="title" content="{{ config('app.name') }} | Dashboard" />
    <meta name="author" content="ColorlibHQ" />
    <meta name="Description"
        content="AdminLTE is a free Bootstrap 5 admin dashboard template with almost 50 example pages, built with vanilla JS and designed with accessibility in mind." />
    <meta name="keywords"
        content="bootstrap 5, bootstrap, bootstrap 5 admin dashboard, bootstrap 5 dashboard, bootstrap 5 charts, bootstrap 5 calendar, bootstrap 5 datepicker, bootstrap 5 tables, bootstrap 5 datatable, vanilla js datatable, colorlibhq, colorlibhq dashboard, colorlibhq admin dashboard, accessible admin panel" />
    <!--end::Primary Meta Tags-->

    <!--begin::Accessibility Features-->
    <!-- Skip links will be dynamically added by accessibility.js -->
    <meta name="supported-color-schemes" content="light dark" />
    <link rel="preload" href="{{ asset('cms/css/adminlte.css') }}" as="style" />
    <!--end::Accessibility Features-->

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fontsource/source-sans-3@5.0.12/index.css"
        integrity="sha256-tXJfXfp6Ewt1ilPzLDtQnJV4hclT9XuaZUKyUvmyr+Q=" crossorigin="anonymous" media="print"
        onload="this.media = 'all'" />
    <!--end::Fonts-->

    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/styles/overlayscrollbars.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(OverlayScrollbars)-->

    <!--begin::Third Party Plugin(Bootstrap Icons)-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css"
        crossorigin="anonymous" />
    <!--end::Third Party Plugin(Bootstrap Icons)-->

    <!--begin::Required Plugin(AdminLTE)-->
    <link rel="stylesheet" href="{{ asset('cms/css/adminlte.css') }}" />
    <!--end::Required Plugin(AdminLTE)-->

    <!-- apexcharts -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.css"
        integrity="sha256-4MX+61mt9NVvvuPjUWdUdyfZfxSB1/Rf9WtqRHgG5S0=" crossorigin="anonymous" />

    <!-- jsvectormap -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/css/jsvectormap.min.css"
        integrity="sha256-+uGLJmmTKOqBr+2E6KDYs/NRsHxSkONXFHUL0fy2O/4=" crossorigin="anonymous" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @yield('styles')
</head>
<!--end::Head-->
<!--begin::Body-->

<body class="layout-fixed sidebar-expand-lg bg-body-tertiary">
    <!--begin::App Wrapper-->
    <div class="app-wrapper">
        <!--begin::Header-->
        <nav class="app-header navbar navbar-expand bg-body">
            <!--begin::Container-->
            <div class="container-fluid">
                <!--begin::Start Navbar Links-->
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" data-lte-toggle="sidebar" href="#" role="button"
                            aria-label="Toggle sidebar">
                            <i class="bi bi-list"></i>
                        </a>
                    </li>

                    <li class="nav-item d-none d-md-block">
                        <a href="./index.html" class="nav-link">
                            <i class="bi bi-grid-1x2 me-1" aria-hidden="true"></i>
                            Live preview
                        </a>
                    </li>
                    <li class="nav-item d-none d-md-block">
                        <a href="./docs/introduction.html" class="nav-link">
                            <i class="bi bi-book me-1" aria-hidden="true"></i>
                            Documentation
                        </a>
                    </li>
                </ul>
                <!--end::Start Navbar Links-->

                <!--begin::End Navbar Links-->
                <ul class="navbar-nav ms-auto">
                    <!--begin::Messages Dropdown Menu-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#" aria-label="Messages: 3 unread">
                            <i class="bi bi-chat-text"></i>
                            <span class="navbar-badge badge text-bg-danger">3</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('cms/assets/img/user1-128x128.jpg') }}" alt=""
                                            class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="dropdown-item-title">
                                            Brad Diesel
                                            <span class="float-end fs-7 text-danger"><i
                                                    class="bi bi-star-fill"></i></span>
                                        </p>
                                        <p class="fs-7">Call me whenever you can...</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('cms/assets/img/user8-128x128.jpg') }}" alt=""
                                            class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="dropdown-item-title">
                                            John Pierce
                                            <span class="float-end fs-7 text-secondary">
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                        </p>
                                        <p class="fs-7">I got your message bro</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <!--begin::Message-->
                                <div class="d-flex">
                                    <div class="flex-shrink-0">
                                        <img src="{{ asset('cms/assets/img/user3-128x128.jpg') }}" alt=""
                                            class="img-size-50 rounded-circle me-3" />
                                    </div>
                                    <div class="flex-grow-1">
                                        <p class="dropdown-item-title">
                                            Nora Silvester
                                            <span class="float-end fs-7 text-warning">
                                                <i class="bi bi-star-fill"></i>
                                            </span>
                                        </p>
                                        <p class="fs-7">The subject goes here</p>
                                        <p class="fs-7 text-secondary">
                                            <i class="bi bi-clock-fill me-1"></i> 4 Hours Ago
                                        </p>
                                    </div>
                                </div>
                                <!--end::Message-->
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer">See All Messages</a>
                        </div>
                    </li>
                    <!--end::Messages Dropdown Menu-->

                    <!--begin::Notifications Dropdown Menu-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" data-bs-toggle="dropdown" href="#"
                            aria-label="Notifications: 15 unread">
                            <i class="bi bi-bell-fill"></i>
                            <span class="navbar-badge badge text-bg-warning">15</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <span class="dropdown-item dropdown-header">15 Notifications</span>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-envelope me-2"></i> 4 new messages
                                <span class="float-end text-secondary fs-7">3 mins</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-people-fill me-2"></i> 8 friend requests
                                <span class="float-end text-secondary fs-7">12 hours</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item">
                                <i class="bi bi-file-earmark-fill me-2"></i> 3 new reports
                                <span class="float-end text-secondary fs-7">2 days</span>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="#" class="dropdown-item dropdown-footer"> See All Notifications </a>
                        </div>
                    </li>
                    <!--end::Notifications Dropdown Menu-->

                    <!--begin::Fullscreen Toggle-->
                    <li class="nav-item">
                        <a class="nav-link" href="#" data-lte-toggle="fullscreen"
                            aria-label="Toggle fullscreen">
                            <i data-lte-icon="maximize" class="bi bi-arrows-fullscreen"></i>
                            <i data-lte-icon="minimize" class="bi bi-fullscreen-exit d-none"></i>
                        </a>
                    </li>
                    <!--end::Fullscreen Toggle-->

                    <!--begin::Color Mode Toggle (#6010)-->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="bd-theme" aria-label="Toggle color scheme"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-sun-fill" data-lte-theme-icon="light"></i>
                            <i class="bi bi-moon-fill d-none" data-lte-theme-icon="dark"></i>
                            <i class="bi bi-circle-half d-none" data-lte-theme-icon="auto"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="bd-theme"
                            style="--bs-dropdown-min-width: 8rem">
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="light" aria-pressed="false">
                                    <i class="bi bi-sun-fill me-2"></i>
                                    Light
                                    <i class="bi bi-check-lg ms-auto d-none"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center"
                                    data-bs-theme-value="dark" aria-pressed="false">
                                    <i class="bi bi-moon-fill me-2"></i>
                                    Dark
                                    <i class="bi bi-check-lg ms-auto d-none"></i>
                                </button>
                            </li>
                            <li>
                                <button type="button" class="dropdown-item d-flex align-items-center active"
                                    data-bs-theme-value="auto" aria-pressed="true">
                                    <i class="bi bi-circle-half me-2"></i>
                                    Auto
                                    <i class="bi bi-check-lg ms-auto d-none"></i>
                                </button>
                            </li>
                        </ul>
                    </li>
                    <!--end::Color Mode Toggle-->

                    <!--begin::User Menu Dropdown-->
                    <li class="nav-item dropdown user-menu">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img src="{{ asset(Auth::user()->profile_image) }}"
                                class="user-image rounded-circle shadow" alt="{{ Auth::user()->name }}" />
                            <span class="d-none d-md-inline">{{ Auth::user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
                            <!--begin::User Image-->
                            <li class="user-header text-bg-primary">
                                <img src="{{ asset(Auth::user()->profile_image) }}" class="rounded-circle shadow"
                                    alt="{{ Auth::user()->name }}" />
                                <p>
                                    {{ Auth::user()->name }} - {{ Auth::user()->roles->pluck('name')->first() }}
                                    <small>Member since {{ Auth::user()->created_at->format('M. Y') }}</small>
                                </p>
                            </li>
                            <!--end::User Image-->
                            <!--begin::Menu Body-->
                            <li class="user-body">
                                <!--begin::Row-->
                                <div class="row">
                                    <div class="col-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                                <!--end::Row-->
                            </li>
                            <!--end::Menu Body-->
                            <!--begin::Menu Footer-->
                            <li class="user-footer">
                                <a href="#" class="btn btn-outline-secondary">Profile</a>
                                <a href="#" class="btn btn-outline-danger float-end">Sign out</a>
                            </li>
                            <!--end::Menu Footer-->
                        </ul>
                    </li>
                    <!--end::User Menu Dropdown-->
                </ul>
                <!--end::End Navbar Links-->
            </div>
            <!--end::Container-->
        </nav>
        <!--end::Header-->
        <!--begin::Sidebar-->
        <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
            <!--begin::Sidebar Brand-->
            <div class="sidebar-brand">
                <!--begin::Brand Link-->
                <a href="{{ asset('cms/index.html') }}" class="brand-link">
                    <!--begin::Brand Image-->
                    <img src="{{ asset('cms/assets/img/AdminLTELogo.png') }}" alt="AdminLTE Logo"
                        class="brand-image opacity-75 shadow" />
                    <!--end::Brand Image-->
                    <!--begin::Brand Text-->
                    <span class="brand-text fw-light">{{ config('app.name') }}</span>
                    <!--end::Brand Text-->
                </a>
                <!--end::Brand Link-->
            </div>
            <!--end::Sidebar Brand-->
            <!--begin::Sidebar Wrapper-->
            <div class="sidebar-wrapper">
                <nav class="mt-2" aria-label="Main navigation">
                    <!--begin::Sidebar Menu-->
                    <ul class="nav sidebar-menu flex-column" data-lte-toggle="treeview" data-accordion="false"
                        id="navigation">

                        {{-- start Roles and Permissions --}}
                        @canAny(['Index Role', 'Create Role', 'Index Permission', 'Create Permission'])
                            <li class="nav-header">Roles & Permissions</li>
                            @canAny(['Index Role', 'Create Role'])
                                <li class="nav-item ">
                                    <a href="#" class="nav-link align-items-center">
                                        <i class="nav-icon fa-solid fa-user-shield"></i>
                                        <p>
                                            Roles
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('Create Role')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.roles.create') }}" class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-circle-plus"></i>
                                                    <p>Create Role</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Index Role')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.roles.index') }}" class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-list"></i>
                                                    <p>Index Roles</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canAny(['Index Permission', 'Create Permission'])
                                <li class="nav-item">
                                    <a href="#" class="nav-link align-items-center">
                                        <i class="nav-icon fa-solid fa-shield-halved"></i>
                                        <p>
                                            Permissions
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('Create Permission')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.permissions.create') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-circle-plus"></i>
                                                    <p>Create Permission</p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Index Permission')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.permissions.index') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-list"></i>
                                                    <p>Index Permissions</p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            <br>
                        @endcanany
                        {{-- end Roles and Permissions --}}
                        {{-- start User Management --}}
                        @canAny([
                            'Index User',
                            'Create User',
                            'Index Admin',
                            'Create Admin',
                            'Index Employee',
                            'Create
                            Employee',
                            'Index Student',
                            'Create Student',
                            ])
                            <li class="nav-header">User Management</li>
                            @canAny(['Index User', 'Create User'])
                                <li class="nav-item">
                                    <a href="#" class="nav-link align-items-center">
                                        <i class="nav-icon fa-solid fa-user"></i>
                                        <p>
                                            Users
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('Create User')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.users.create') }}" class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-circle-plus"></i>
                                                    <p>Create users </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Index User')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.users.index') }}" class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-list"></i>
                                                    <p>Index Users </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canAny(['Index Admin', 'Create Admin'])
                                <li class="nav-item">
                                    <a href="#" class="nav-link align-items-center">
                                        <i class="nav-icon fa-solid fa-user-gear"></i>
                                        <p>
                                            Admins
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('Create Admin')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.admins.create') }}" class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-circle-plus"></i>
                                                    <p>Create admins </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Index Admin')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.admins.index') }}" class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-list"></i>
                                                    <p>Index Admins </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canAny(['Index Employee', 'Create Employee'])
                                <li class="nav-item">
                                    <a href="#" class="nav-link align-items-center">
                                        <i class="nav-icon fa-solid fa-user-tie"></i>
                                        <p>
                                            Employees
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('Create Employee')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.employees.create') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-circle-plus"></i>
                                                    <p>Create employees </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Index Employee')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.employees.index') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-list"></i>
                                                    <p>Index Employees </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canAny(['Index Student', 'Create Student'])
                                <li class="nav-item">
                                    <a href="#" class="nav-link align-items-center">
                                        <i class="nav-icon fa-solid fa-user-graduate"></i>
                                        <p>
                                            Students
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('Create Student')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.students.create') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-circle-plus"></i>
                                                    <p>Create students </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Index Student')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.students.index') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-list"></i>
                                                    <p>Index Students </p>
                                                </a>
                                            </li>
                                        @endcan

                                    </ul>
                                </li>
                            @endcanany
                            <br>
                        @endcanany

                        {{-- end User Management --}}

                        {{-- start Content Management --}}
                        @canAny([
                            'Index Deanship',
                            'Create Deanship',
                            'Index Department',
                            'Create Department',
                            'Index Major',
                            'Create Major',
                            'Index Category',
                            'Create Category',
                            'Index Issue',
                            'Create Issue',
                            ])
                            <li class="nav-header">Content Management</li>
                            @canAny(['Index Deanship', 'Create Deanship'])
                                <li class="nav-item">
                                    <a href="#" class="nav-link  align-items-center">
                                        <i class="nav-icon fa-solid fa-building-columns"></i>
                                        <p>
                                            Deanships
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('Create Deanship')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.deanships.create') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-circle-plus"></i>
                                                    <p>Create Deanship </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Index Deanship')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.deanships.index') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-list"></i>
                                                    <p>Index Deanships </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canAny(['Index Department', 'Create Department'])
                                <li class="nav-item">
                                    <a href="#" class="nav-link  align-items-center">
                                        <i class="nav-icon fa-solid fa-sitemap"></i>
                                        <p>
                                            Departments
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('Create Department')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.departments.create') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-circle-plus"></i>
                                                    <p>Create Department </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Index Department')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.departments.index') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-list"></i>
                                                    <p>Index Departments </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canAny(['Index Major', 'Create Major'])
                                <li class="nav-item">
                                    <a href="#" class="nav-link  align-items-center">
                                        <i class="nav-icon fa-solid fa-graduation-cap"></i>
                                        <p>
                                            Majors
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('Create Major')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.majors.create') }}" class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-circle-plus"></i>
                                                    <p>Create Major </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Index Major')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.majors.index') }}" class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-list"></i>
                                                    <p>Index Majors </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canAny(['Index Category', 'Create Category'])
                                <li class="nav-item">
                                    <a href="#" class="nav-link  align-items-center">
                                        <i class="nav-icon fa-solid fa-layer-group"></i>
                                        <p>
                                            Categories
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('Create Category')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.categories.create') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-circle-plus"></i>
                                                    <p>Create Category </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Index Category')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.categories.index') }}"
                                                    class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-list"></i>
                                                    <p>Index Categories </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            @canAny(['Index Issue', 'Create Issue'])
                                <li class="nav-item">
                                    <a href="#" class="nav-link  align-items-center">
                                        <i class="nav-icon fa-solid fa-layer-group"></i>
                                        <p>
                                            Issues
                                            <i class="nav-arrow bi bi-chevron-right"></i>
                                        </p>
                                    </a>
                                    <ul class="nav nav-treeview">
                                        @can('Create Issue')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.issues.create') }}" class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-circle-plus"></i>
                                                    <p>Create Issue </p>
                                                </a>
                                            </li>
                                        @endcan
                                        @can('Index Issue')
                                            <li class="nav-item">
                                                <a href="{{ route('admin.issues.index') }}" class="nav-link align-items-center">
                                                    <i class="nav-icon fa-solid fa-list"></i>
                                                    <p>Index Issues </p>
                                                </a>
                                            </li>
                                        @endcan
                                    </ul>
                                </li>
                            @endcanany
                            <br>
                        @endcanany
                        {{-- end Content Management --}}
                        {{-- start Authentications --}}
                        <li class="nav-header">Authentications</li>
                        <li class="nav-item">
                            <a href="./users.html" class="nav-link align-items-center">
                                <i class="fa-solid fa-arrow-right-to-bracket"></i>
                                <p>Register</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./users.html" class="nav-link align-items-center">
                                <i class="fa-solid fa-user-pen"></i>
                                <p>Edit Profile</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="./users.html" class="nav-link align-items-center">
                                <i class="fa-solid fa-pen-to-square"></i>
                                <p>Change Password</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link align-items-center"
                                onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                <p>Logout</p>
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </li>
                        {{-- end Authentications --}}
                    </ul>
                    <!--end::Sidebar Menu-->

                </nav>
            </div>
            <!--end::Sidebar Wrapper-->
        </aside>
        <!--end::Sidebar-->
        <!--begin::App Main-->
        <main class="app-main">
            <!--begin::App Content Header-->
            <div class="app-content-header">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        <div class="col-sm-6">
                            <h1 class="mb-0 fs-3">@yield('main-title', 'Dashboard')</h1>
                        </div>
                        <div class="col-sm-6">
                            <nav aria-label="breadcrumb">
                                <ol class="breadcrumb float-sm-end">
                                    <li class="breadcrumb-item"><a href="#">Home</a></li>
                                    <li class="breadcrumb-item active" aria-current="page">@yield('sub-title', 'Dashboard')</li>
                                </ol>
                            </nav>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content Header-->
            <!--begin::App Content-->
            <div class="app-content">
                <!--begin::Container-->
                <div class="container-fluid">
                    <!--begin::Row-->
                    <div class="row">
                        @yield('content')

                    </div>
                    <!-- /.row (main row) -->
                </div>
                <!--end::Container-->
            </div>
            <!--end::App Content-->
        </main>
        <!--end::App Main-->
        <!--begin::Footer-->
        <footer class="app-footer">
            <!--begin::To the end-->
            <div class="float-end d-none d-sm-inline">Version: {{ env('APP_VERSION') }}</div>
            <!--end::To the end-->
            <!--begin::Copyright-->
            <strong>
                Copyright &copy; {{ now()->year }}-{{ date('Y') + 1 }}&nbsp;
                <a href="https://adminlte.io" class="text-decoration-none">{{ config('app.name') }}</a>.
            </strong>
            All rights reserved.
            <!--end::Copyright-->
        </footer>
        <!--end::Footer-->
    </div>
    <!--end::App Wrapper-->
    <!--begin::Script-->
    <!--begin::Third Party Plugin(OverlayScrollbars)-->
    <script src="https://cdn.jsdelivr.net/npm/overlayscrollbars@2.11.0/browser/overlayscrollbars.browser.es6.min.js"
        crossorigin="anonymous"></script>
    <!--end::Third Party Plugin(OverlayScrollbars)--><!--begin::Required Plugin(popperjs for Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" crossorigin="anonymous">
    </script>
    <!--end::Required Plugin(popperjs for Bootstrap 5)--><!--begin::Required Plugin(Bootstrap 5)-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.min.js" crossorigin="anonymous"></script>
    <!--end::Required Plugin(Bootstrap 5)--><!--begin::Required Plugin(AdminLTE)-->
    <script src="{{ asset('cms/js/adminlte.js') }}"></script>
    <!--end::Required Plugin(AdminLTE)-->
    <!--begin::OverlayScrollbars Configure-->
    <script>
        const SELECTOR_SIDEBAR_WRAPPER = '.sidebar-wrapper';
        const Default = {
            scrollbarTheme: 'os-theme-light',
            scrollbarAutoHide: 'leave',
            scrollbarClickScroll: true,
        };
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarWrapper = document.querySelector(SELECTOR_SIDEBAR_WRAPPER);

            // Disable OverlayScrollbars on mobile devices to prevent touch interference
            const isMobile = window.innerWidth <= 992;

            if (
                sidebarWrapper &&
                OverlayScrollbarsGlobal?.OverlayScrollbars !== undefined &&
                !isMobile
            ) {
                OverlayScrollbarsGlobal.OverlayScrollbars(sidebarWrapper, {
                    scrollbars: {
                        theme: Default.scrollbarTheme,
                        autoHide: Default.scrollbarAutoHide,
                        clickScroll: Default.scrollbarClickScroll,
                    },
                });
            }
        });
    </script>
    <!--end::OverlayScrollbars Configure-->

    <!--begin::Color Mode Toggle-->
    <!-- The light/dark/auto switcher ships in adminlte.js as the ColorMode
 module (since 4.1) — no page script needed. Only the no-flash snippet
 in <head> stays inline, because it must run before first paint. -->
    <!--end::Color Mode Toggle-->

    <!-- OPTIONAL SCRIPTS -->

    <!-- sortablejs -->
    <script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js" crossorigin="anonymous"></script>
    <!-- sortablejs -->
    <script>
        const sortableElement = document.querySelector('.connectedSortable');

        if (sortableElement) {
            new Sortable(sortableElement, {
                group: 'shared',
                handle: '.card-header',
            });

            const cardHeaders = document.querySelectorAll('.connectedSortable .card-header');

            cardHeaders.forEach((cardHeader) => {
                cardHeader.style.cursor = 'move';
            });
        }
    </script>
    <!-- apexcharts -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts@3.37.1/dist/apexcharts.min.js"
        integrity="sha256-+vh8GkaU7C9/wbSLIcwq82tQ2wTf44aOHA8HlBMwRI8=" crossorigin="anonymous"></script>
    <!-- ChartJS -->
    <script>
        // NOTICE!! DO NOT USE ANY OF THIS JAVASCRIPT
        // IT'S ALL JUST JUNK FOR DEMO
        // ++++++++++++++++++++++++++++++++++++++++++

        const sales_chart_options = {
            series: [{
                    name: 'Digital Goods',
                    data: [28, 48, 40, 19, 86, 27, 90],
                },
                {
                    name: 'Electronics',
                    data: [65, 59, 80, 81, 56, 55, 40],
                },
            ],
            chart: {
                height: 300,
                type: 'area',
                toolbar: {
                    show: false,
                },
            },
            legend: {
                show: false,
            },
            colors: ['#0d6efd', '#20c997'],
            dataLabels: {
                enabled: false,
            },
            stroke: {
                curve: 'smooth',
            },
            xaxis: {
                type: 'datetime',
                categories: [
                    '2023-01-01',
                    '2023-02-01',
                    '2023-03-01',
                    '2023-04-01',
                    '2023-05-01',
                    '2023-06-01',
                    '2023-07-01',
                ],
            },
            tooltip: {
                x: {
                    format: 'MMMM yyyy',
                },
            },
        };

        const revenueChart = document.querySelector('#revenue-chart');

        if (revenueChart) {
            const sales_chart = new ApexCharts(
                revenueChart,
                sales_chart_options,
            );

            sales_chart.render();
        }
    </script>
    <!-- jsvectormap -->
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/js/jsvectormap.min.js"
        integrity="sha256-/t1nN2956BT869E6H4V1dnt0X5pAQHPytli+1nTZm2Y=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsvectormap@1.5.3/dist/maps/world.js"
        integrity="sha256-XPpPaZlU8S/HWf7FZLAncLg2SAkP8ScUTII89x9D3lY=" crossorigin="anonymous"></script>
    <!-- jsvectormap -->
    <script>
        // World map by jsVectorMap
        const worldMap = document.querySelector('#world-map');
        if (worldMap) {
            new jsVectorMap({
                selector: '#world-map',
                map: 'world',
            });
        }

        // Sparkline charts
        const option_sparkline1 = {
            series: [{
                data: [1000, 1200, 920, 927, 931, 1027, 819, 930, 1021],
            }, ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparklineElement1 = document.querySelector('#sparkline-1');
        if (sparklineElement1) {
            const sparkline1 = new ApexCharts(
                sparklineElement1,
                option_sparkline1
            );

            sparkline1.render();
        }

        const option_sparkline2 = {
            series: [{
                data: [515, 519, 520, 522, 652, 810, 370, 627, 319, 630, 921],
            }, ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparklineElement2 = document.querySelector('#sparkline-2');
        if (sparklineElement2) {
            const sparkline2 = new ApexCharts(
                sparklineElement2,
                option_sparkline2
            );

            sparkline2.render();
        }

        const option_sparkline3 = {
            series: [{
                data: [15, 19, 20, 22, 33, 27, 31, 27, 19, 30, 21],
            }, ],
            chart: {
                type: 'area',
                height: 50,
                sparkline: {
                    enabled: true,
                },
            },
            stroke: {
                curve: 'straight',
            },
            fill: {
                opacity: 0.3,
            },
            yaxis: {
                min: 0,
            },
            colors: ['#DCE6EC'],
        };

        const sparklineElement3 = document.querySelector('#sparkline-3');
        if (sparklineElement3) {
            const sparkline3 = new ApexCharts(
                sparklineElement3,
                option_sparkline3
            );

            sparkline3.render();
        }
    </script>
    @yield('scripts')
    <!--end::Script-->
</body>
<!--end::Body-->

</html>
