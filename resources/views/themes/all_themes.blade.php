<!DOCTYPE html>
<html>
<head>
    <title>Tous les Thèmes</title>
    <link rel="stylesheet" href="{{asset('css/themes/themes.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="themes-container">
        <div class="header">
            <h1>Tous les Thèmes</h1>
            <div class="user-info">
                <div class="user-profile">
                    <a href="{{ route('subscriber.dashboard') }}" class="user-avatar">
                        <i class="fas fa-user-circle"></i>
                        <span>{{ Auth::user()->name }}</span>
                    </a>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="logout-form">
                    @csrf
                    <button type="submit">Déconnexion</button>
                </form>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">
                {{ session('error') }}
            </div>
        @endif

        @if(session('info'))
            <div class="alert alert-info">
                {{ session('info') }}
            </div>
        @endif

        <div class="themes-grid">
            @if($themes->count() > 0)
                @foreach($themes as $theme)
                    <div class="theme-card">
                        <div class="theme-header">
                            <h3>{{ $theme->title }}</h3>
                            <span class="theme-manager">
                                Géré par: {{ $theme->themeManager->name }}
                            </span>
                        </div>
                        <p class="theme-description">{{ $theme->discription }}</p>
                        <div class="theme-status">
                            Statut: <span class="{{ $theme->is_accept ? 'accepted' : 'pending' }}">
                                {{ $theme->is_accept ? 'Accepté' : 'En attente' }}
                            </span>
                        </div>
                        <div class="theme-actions">
                            @if($theme->user_id !== Auth::id())
                                @if($theme->users->contains(Auth::id()))
                                    <form action="{{ route('themes.unsubscribe', $theme->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button type="submit" class="unsubscribe-btn">
                                            <i class="fas fa-user-minus"></i>
                                            <span>Se désabonner</span>
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('themes.subscribe', $theme->id) }}" method="POST" class="inline-form">
                                        @csrf
                                        <button type="submit" class="subscribe-btn">
                                            <i class="fas fa-user-plus"></i>
                                            <span>S'abonner</span>
                                        </button>
                                    </form>
                                @endif
                            @endif
                            <a href="{{ route('articles.theme', $theme) }}" class="view-articles-btn">
                                <i class="fas fa-book-reader"></i>
                                <span>Voir les articles</span>
                            </a>
                        </div>
                    </div>
                @endforeach
            @else
                <div class="no-themes">
                    <p>Aucun thème n'est disponible pour le moment.</p>
                </div>
            @endif
        </div>

        <div class="back-button">
            <a href="{{ route('hometest') }}" class="btn-back">Retour à l'accueil</a>
        </div>
    </div>
</body>
</html>
