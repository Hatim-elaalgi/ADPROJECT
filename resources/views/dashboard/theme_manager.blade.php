<!DOCTYPE html>
<html>
<head>
    <title>Theme Manager Dashboard</title>
    <link rel="stylesheet" href="{{asset('css/dashboard/theme_manager.css')}}">
</head>
<body>
    <div class="dashboard-container">
        <div class="header">
            <h1>Theme Manager Dashboard</h1>
            <div class="user-info">
                <span>Welcome, {{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit">Logout</button>
                </form>
            </div>
        </div>

        <div class="themes-section">
            <h2>Your Managed Themes</h2>
            @if($managedThemes->count() > 0)
                <div class="themes-grid">
                    @foreach($managedThemes as $theme)
                        <div class="theme-card">
                            <h3>{{ $theme->title }}</h3>
                            <p>{{ $theme->discription }}</p>
                            <div class="theme-status">
                                Status: {{ $theme->is_accept ? 'Accepted' : 'Pending' }}
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="no-themes">You don't have any themes to manage yet.</p>
            @endif
        </div>
    </div>
</body>
</html>
