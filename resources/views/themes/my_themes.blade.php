@php
    use App\Models\User;
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Mes Thèmes</title>
    <link rel="stylesheet" href="{{asset('css/themes/themes.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="themes-container">
        <div class="header">
            <h1>Mes Thèmes</h1>
            <div class="user-info">
                <span>{{ Auth::user()->name }}</span>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit">Déconnexion</button>
                </form>
            </div>
        </div>

        <div class="themes-grid">
            @if($themes->count() > 0)
                @foreach($themes as $theme)
                    <div class="theme-card">
                        <div class="theme-header">
                            <h3>{{ $theme->title }}</h3>
                            @if($userRole === User::ROLE_SUBSCRIBER)
                                <span class="theme-manager">
                                    Géré par: {{ $theme->themeManager->name }}
                                </span>
                            @endif
                        </div>
                        <p class="theme-description">{{ $theme->discription }}</p>
                        <div class="theme-status">
                            Statut: <span class="{{ $theme->is_accept ? 'accepted' : 'pending' }}">
                                {{ $theme->is_accept ? 'Accepté' : 'En attente' }}
                            </span>
                        </div>
                        <div class="theme-actions">
                            @if($userRole === User::ROLE_RESPONSABLE_THEME)
                                <a href="{{ route('themes.manage', $theme->id) }}" class="manage-btn">
                                    <i class="fas fa-cog"></i> Gérer le thème
                                </a>
                            @endif
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-themes">
                    <p>Vous n'avez pas encore de thèmes.</p>
                </div>
            @endif
        </div>

        <div class="back-button">
            <a href="{{ route('hometest') }}" class="btn-back">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
