<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- If your custom script is NOT managed by Vite, but a static file in public/js --}}
    {{--
    <script src="{{ asset('js/script.js') }}" defer></script> --}}

    {{-- If you're using Bootstrap or another framework, you need to include its CSS here --}}
    {{--
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
    {{--
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}

    <style>
        /* Add your custom sidebar specific CSS here or in resources/css/app.css
           It's crucial to write CSS that complements Tailwind, not conflicts.
           Tailwind classes are utility-first, so you might need to override/add specific styles.
        */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            height: 100%;
            width: 260px;
            background: #11101d;
            z-index: 100;
            transition: all 0.5s ease;
        }

        .sidebar.close {
            width: 78px;
        }

        .sidebar .logo-details {
            height: 60px;
            width: 100%;
            display: flex;
            align-items: center;
        }

        .sidebar .logo-details i {
            font-size: 30px;
            color: #fff;
            height: 50px;
            min-width: 78px;
            text-align: center;
            line-height: 50px;
        }

        .sidebar .logo-details .logo_name {
            font-size: 22px;
            color: #fff;
            font-weight: 600;
            transition: 0.3s ease;
            transition-delay: 0.1s;
        }

        .sidebar.close .logo-details .logo_name {
            transition-delay: 0s;
            opacity: 0;
            pointer-events: none;
        }

        .sidebar .nav-links {
            height: 100%;
            padding: 30px 0 150px 0;
            overflow: auto;
        }

        .sidebar.close .nav-links {
            overflow: visible;
        }

        .sidebar .nav-links::-webkit-scrollbar {
            display: none;
        }

        .sidebar .nav-links li {
            position: relative;
            list-style: none;
            transition: all 0.4s ease;
        }

        .sidebar .nav-links li:hover {
            background: #1d1b31;
        }

        .sidebar .nav-links li .iocn-link {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .sidebar.close .nav-links li .iocn-link {
            display: block
        }

        .sidebar .nav-links li i {
            height: 50px;
            min-width: 78px;
            text-align: center;
            line-height: 50px;
            color: #fff;
            font-size: 20px;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .sidebar .nav-links li.showMenu i.arrow {
            transform: rotate(-180deg);
        }

        .sidebar.close .nav-links i.arrow {
            display: none;
        }

        .sidebar .nav-links li a {
            display: flex;
            align-items: center;
            text-decoration: none;
        }

        .sidebar .nav-links li a .link_name {
            font-size: 18px;
            font-weight: 400;
            color: #fff;
            transition: all 0.4s ease;
        }

        .sidebar.close .nav-links li a .link_name {
            opacity: 0;
            pointer-events: none;
        }

        .sidebar .nav-links li .sub-menu {
            padding: 6px 6px 14px 80px;
            margin-top: -10px;
            background: #1d1b31;
            display: none;
        }

        .sidebar .nav-links li.showMenu .sub-menu {
            display: block;
        }

        .sidebar .nav-links li .sub-menu a {
            color: #fff;
            font-size: 15px;
            padding: 5px 0;
            white-space: nowrap;
            opacity: 0.6;
            transition: all 0.3s ease;
        }

        .sidebar .nav-links li .sub-menu a:hover {
            opacity: 1;
        }

        .sidebar.close .nav-links li .sub-menu {
            position: absolute;
            left: 100%;
            top: -10px;
            margin-top: 0;
            padding: 10px 20px;
            border-radius: 0 6px 6px 0;
            opacity: 0;
            display: block;
            pointer-events: none;
            transition: 0s;
        }

        .sidebar.close .nav-links li:hover .sub-menu {
            top: 0;
            opacity: 1;
            pointer-events: auto;
            transition: all 0.4s ease;
        }

        .sidebar .nav-links li .sub-menu .link_name {
            display: none;
        }

        .sidebar.close .nav-links li .sub-menu .link_name {
            font-size: 18px;
            opacity: 1;
            display: block;
        }

        .sidebar .nav-links li .sub-menu.blank {
            opacity: 1;
            pointer-events: auto;
            padding: 3px 20px 6px 16px;
            opacity: 0;
            pointer-events: none;
        }

        .sidebar .nav-links li:hover .sub-menu.blank {
            top: 50%;
            transform: translateY(-50%);
        }

        .sidebar .profile-details {
            position: fixed;
            bottom: 0;
            width: 260px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: #1d1b31;
            padding: 12px 0;
            transition: all 0.5s ease;
        }

        .sidebar.close .profile-details {
            background: none;
        }

        .sidebar.close .profile-details {
            width: 78px;
        }

        .sidebar .profile-details .profile-content {
            display: flex;
            align-items: center;
        }

        .sidebar .profile-details img {
            height: 52px;
            width: 52px;
            object-fit: cover;
            border-radius: 16px;
            margin: 0 14px 0 12px;
            background: #1d1b31;
            transition: all 0.5s ease;
        }

        .sidebar.close .profile-details img {
            padding: 10px;
        }

        .sidebar .profile-details .profile_name,
        .sidebar .profile-details .job {
            color: #fff;
            font-size: 18px;
            font-weight: 500;
            white-space: nowrap;
            text-indent: 20px;
        }

        .sidebar.close .profile-details i,
        .sidebar.close .profile-details .profile_name,
        .sidebar.close .profile-details .job {
            display: none;
        }

        .sidebar .profile-details .job {
            font-size: 12px;
        }

        .home-section {
            position: relative;
            background: #E4E9F7;
            height: 100vh;
            left: 260px;
            width: calc(100% - 260px);
            transition: all 0.5s ease;
            padding: 12px;
        }

        .sidebar.close~.home-section {
            left: 78px;
            width: calc(100% - 78px);
        }

        .home-content {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .home-section .home-content .bx-menu,
        .home-section .home-content .text {
            color: #11101d;
            font-size: 35px;
        }

        .home-section .home-content .bx-menu {
            cursor: pointer;
            margin-right: 10px;
        }

        .home-section .home-content .text {
            font-size: 26px;
            font-weight: 600;
        }

        .possi-logo {
            width: 50px;
            height: 50px;
            /* Optional: maintain aspect ratio */
            object-fit: contain;
        }

        @media screen and (max-width: 400px) {
            .sidebar {
                width: 240px;
            }

            .sidebar.close {
                width: 78px;
            }

            .sidebar .profile-details {
                width: 240px;
            }

            .sidebar.close .profile-details {
                background: none;
            }

            .sidebar.close .profile-details {
                width: 78px;
            }

            .home-section {
                left: 240px;
                width: calc(100% - 240px);
            }

            .sidebar.close~.home-section {
                left: 78px;
                width: calc(100% - 78px);
            }
        }
    </style>
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 flex"> {{-- Add flex container to body's main div --}}

        {{-- <div class="sidebar close"> --}}
            <div class="sidebar">
                <div class="logo-details">
                    {{-- <i class='bx bxl-c-plus-plus'></i> --}}
                    <img src="{{ asset('image/possi_logo.png') }}" alt="possilogo" class="possi-logo">
                    <span class="logo_name">Possi Jatim</span>
                </div>
                <ul class="nav-links">
                    {{-- <li>
                        <a href="#">
                            <i class='bx bx-grid-alt'></i>
                            <span class="link_name">Dashboard</span>
                        </a>
                        <ul class="sub-menu blank">
                            <li><a class="link_name" href="#">Category</a></li>
                        </ul>
                    </li> --}}
                    <li>
                        <div class="iocn-link">
                            <a href="#">
                                {{-- <i class='bx bx-collection'></i> --}}
                                <span class="link_name">Form A1</span>
                            </a>
                            <i class='bx bxs-chevron-down arrow'></i>
                        </div>
                        <ul class="sub-menu">
                            <li><a class="link_name" href="#">Category</a></li>
                            <li><a href="#">Kontingen Kota/Kab</a></li>
                            <li><a href="#">Nama Atlet</a></li>
                        </ul>
                    </li>
                    <li>
                        <div class="iocn-link">
                            <a href="#">
                                {{-- <i class='bx bx-book-alt'></i> --}}
                                <span class="link_name">Form A3</span>
                            </a>
                            <i class='bx bxs-chevron-down arrow'></i>
                        </div>
                        <ul class="sub-menu">
                            <li><a class="link_name" href="#">Posts</a></li>
                            <li><a href="#">Nomor Perorangan</a></li>
                            <li><a href="#">Nomor Estafet</a></li>
                        </ul>
                    </li>
                    <li>
                        <div class="iocn-link">
                            <a href="#">
                                {{-- <i class='bx bx-pie-chart-alt-2'></i> --}}
                                <span class="link_name">Kirim ke Panitia</span>
                            </a>
                            <i class='bx bxs-chevron-down arrow'></i>
                        </div>
                        <ul class="sub-menu">
                            <li><a class="link_name" href="#">Analytics</a></li>
                            <li><a href="#">Form A3 untuk Panitia</a></li>
                        </ul>
                    </li>
                    <li>
                        <div class="iocn-link">
                            <a href="#">
                                {{-- <i class='bx bx-line-chart'></i> --}}
                                <span class="link_name">Biaya</span>
                            </a>
                            <i class='bx bxs-chevron-down arrow'></i>
                        </div>
                        <ul class="sub-menu">
                            <li><a class="link_name" href="#">biaya</a></li>
                            <li><a href="#">Total Biaya</a></li>
                        </ul>
                    </li>
                    <li>
                        <div class="iocn-link">
                            <a href="#">
                                {{-- <i class='bx bx-plug'></i> --}}
                                <span class="link_name">Cek Hasil Entry(opsional)</span>
                            </a>
                            <i class='bx bxs-chevron-down arrow'></i>
                        </div>
                        <ul class="sub-menu">
                            <li><a class="link_name" href="#">cek_hasil_entry</a></li>
                            <li><a href="#">Cek Hasil Entry Form A3</a></li>
                        </ul>
                    </li>
                    {{-- <li>
                        <a href="#">
                            <i class='bx bx-compass'></i>
                            <span class="link_name">Explore</span>
                        </a>
                        <ul class="sub-menu blank">
                            <li><a class="link_name" href="#">Explore</a></li>
                        </ul>
                    </li>
                    <li>
                        <a href="#">
                            <i class='bx bx-history'></i>
                            <span class="link_name">History</span>
                        </a>
                        <ul class="sub-menu blank">
                            <li><a class="link_name" href="#">History</a></li>
                        </ul>
                    </li> --}}
                    @role('admin')
                    <li>
                        <a href="{{ route('settings') }}">
                            <i class='bx bx-cog'></i>
                            <span class="link_name">Setting</span>
                        </a>
                        <ul class="sub-menu blank">
                            <li><a class="link_name" href="{{ route('settings') }}">Setting</a></li> {{-- Also update this if it's a sub-menu link --}}
                            {{-- You might add more specific admin links here, e.g., --}}
                            <li><a href="{{ route('admin.users.index') }}">Manage Users</a></li>
                            <li><a href="{{ route('admin.roles.index') }}">Manage Roles</a></li>
                        </ul>
                    </li>
                    @endrole
                    <li>
                        <div class="profile-details">
                            <div class="profile-content">
                                <img src="{{ asset('image/harald_gloocker.jpeg') }}" alt="profileImg">
                            </div>
                            <div class="name-job">
                                {{-- <div class="profile_name">Igun Pro Maxx</div> --}}
                                <div class="profile_name">{{ Auth::user()->name }}</div>
                                <div class="job">Web Designer</div>
                            </div>
                            {{-- <i class='bx bx-log-out'></i> --}}
                            <form method="POST" action="{{ route('logout') }}" class="logout-form"> {{-- Added a class
                                for potential styling if needed --}}
                                @csrf

                                {{-- The bx-log-out icon will trigger the logout --}}
                                <i class='bx bx-log-out'
                                    onclick="event.preventDefault(); this.closest('form').submit();"></i>
                            </form>
                        </div>
                    </li>
                </ul>
            </div>



            <section class="home-section">
                {{-- <div class="home-content">
                    <i cla ss='bx bx-menu'></i> --}}
                    {{-- <span class="text">Drop Down Sidebar</span>
                </div> --}}

                {{-- Main content area (including header and slot) needs to be inside the flex container --}}
                <div class="flex-1">
                    @include('layouts.navigation')

                    @isset($header)
                        <header class="bg-white dark:bg-gray-800 shadow">
                            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                                {{ $header }}
                            </div>
                        </header>
                    @endisset

                    <main class="p-4">
                        {{ $slot }}
                    </main>
                </div>

                {{-- Main content area --}}
                {{-- <div class="flex-1">
                    @include('layouts.navigation')

                    @isset($header)
                    <header class="bg-white dark:bg-gray-800 shadow">
                        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                    @endisset

                    <main class="p-4">

                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-6">
                            @role('admin')
                            <p class="text-gray-900 dark:text-gray-100 mb-2">Welcome, Admin! You have access to
                                administrative features.</p>
                            <a href="/admin/dashboard" class="text-indigo-600 dark:text-indigo-400 hover:underline">Go
                                to Admin Dashboard</a>
                            @else

                            @auth
                            <p class="text-gray-900 dark:text-gray-100">Welcome, {{ Auth::user()->name }}. You are a
                                regular user.</p>
                            @else
                            <p class="text-gray-900 dark:text-gray-100">Welcome, Guest! Please log in.</p>
                            @endauth
                            @endrole
                        </div>

                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-6">
                            @hasrole('admin')
                            <p class="text-gray-900 dark:text-gray-100">This content is only visible to users with the
                                'admin' role (using @hasrole).</p>
                            @endhasrole
                        </div>

                        <div class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg p-6 mb-6">
                            @if(Auth::check() && Auth::user()->hasRole('admin'))
                            <p class="text-gray-900 dark:text-gray-100">You are logged in and are an admin (using @if
                                with hasRole()).</p>
                            @endif
                        </div>

                        {{ $slot }}
                    </main>
                </div> --}}
            </section>

        </div> {{-- End of the main flex container --}}

        {{-- Link your custom script properly.
        If it's managed by Vite, it should be imported into resources/js/app.js.
        If it's a standalone file in public, use asset().
        Place it at the end of the body for better performance.
        --}}
        {{--
        <script src="{{ asset('js/script.js') }}"></script> Assuming script.js is in public/js/ --}}

        {{-- If you're using Bootstrap JS --}}
        {{--
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script> --}}

</body>

</html>