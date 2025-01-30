<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Tech Horizons') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        /* Reset CSS */
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Variables */
        :root {
            --primary-color: #3498db;
            --text-color: #2c3e50;
            --bg-color: #f8f9fa;
            --white: #ffffff;
            --gray: #7f8c8d;
            --shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        /* Base Styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: var(--bg-color);
            color: var(--text-color);
            line-height: 1.6;
        }

        /* Layout */
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        /* Navigation */
        .navbar {
            background: var(--white);
            box-shadow: var(--shadow);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .nav-brand {
            font-size: 1.5rem;
            font-weight: 600;
            color: var(--text-color);
            text-decoration: none;
        }

        .nav-menu {
            display: flex;
            list-style: none;
            gap: 2rem;
            margin: 0;
            padding: 0;
        }

        .nav-item {
            position: relative;
        }

        .nav-link {
            color: var(--text-color);
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem;
            transition: color 0.3s ease;
        }

        .nav-link:hover {
            color: var(--primary-color);
        }

        /* Dropdown */
        .dropdown-content {
            display: none;
            position: absolute;
            right: 0;
            top: 100%;
            min-width: 160px;
            background: var(--white);
            box-shadow: var(--shadow);
            border-radius: 4px;
            z-index: 1;
        }

        .nav-item:hover .dropdown-content {
            display: block;
        }

        .dropdown-link {
            display: block;
            padding: 0.75rem 1rem;
            color: var(--text-color);
            text-decoration: none;
        }

        .dropdown-link:hover {
            background: var(--bg-color);
        }

        /* Mobile Menu Toggle */
        .nav-toggle {
            display: none;
            background: none;
            border: none;
            font-size: 1.5rem;
            cursor: pointer;
            color: var(--text-color);
        }

        /* Forms */
        .form-hidden {
            display: none;
        }

        /* Responsive Design */
        @media (max-width: 768px) {
            .nav-toggle {
                display: block;
            }

            .nav-menu {
                display: none;
                position: absolute;
                top: 100%;
                left: 0;
                width: 100%;
                background: var(--white);
                flex-direction: column;
                padding: 1rem;
                box-shadow: var(--shadow);
            }

            .nav-menu.active {
                display: flex;
            }

            .nav-item {
                width: 100%;
            }

            .dropdown-content {
                position: static;
                box-shadow: none;
                padding-left: 1rem;
            }
        }

        /* Main Content */
        main {
            padding: 2rem 0;
        }
    </style>

    @yield('styles')
</head>
<body>
    <nav class="navbar">
        <div class="container nav-content">
            <a href="{{ url('/') }}" class="nav-brand">
                Tech Horizons
            </a>

            <button class="nav-toggle" onclick="toggleMenu()">☰</button>

            <ul class="nav-menu" id="navMenu">
                @auth
                    @if(auth()->user()->role === 'admin')
                        <li class="nav-item">
                            <a href="{{ route('admin.dashboard') }}" class="nav-link">Dashboard</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.users') }}" class="nav-link">Utilisateurs</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.themes') }}" class="nav-link">Thèmes</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.articles') }}" class="nav-link">Articles</a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('admin.numeros') }}" class="nav-link">Numéros</a>
                        </li>
                    @endif

                    <li class="nav-item">
                        <a href="#" class="nav-link">{{ Auth::user()->name }}</a>
                        <div class="dropdown-content">
                            <a href="#" class="dropdown-link" 
                               onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                {{ __('Logout') }}
                            </a>
                        </div>
                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="form-hidden">
                            @csrf
                        </form>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">{{ __('Login') }}</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">{{ __('Register') }}</a>
                    </li>
                @endauth
            </ul>
        </div>
    </nav>

    <main>
        <div class="container">
            @yield('content')
        </div>
    </main>

    <script>
        function toggleMenu() {
            const menu = document.getElementById('navMenu');
            menu.classList.toggle('active');
        }
    </script>

    @yield('scripts')
</body>
</html>