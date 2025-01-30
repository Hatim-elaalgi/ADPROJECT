@php
    use App\Models\User;
    $statusMap = [
        'en_cours' => 'En cours',
        'publie' => 'Publié',
        'refuse' => 'Refus',
        'retenu' => 'Retenu'
    ];
    $reverseStatusMap = array_flip($statusMap);
@endphp
<!DOCTYPE html>
<html>
<head>
    <title>Gestion du Thème - {{ $theme->title }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-id" content="{{ $theme->id }}">
    <link rel="stylesheet" href="{{asset('css/theme_management/dashboard.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="sidebar-header">
                <img src="{{asset('images/logo.png')}}" alt="Logo" class="logo">
                <h3>{{ Auth::user()->name }}</h3>
                <p class="role">Gestionnaire de Thème</p>
            </div>
            <nav class="sidebar-nav">
                <a href="#overview" class="active" data-section="overview">
                    <i class="fas fa-home"></i> Vue d'ensemble
                </a>
                <a href="#articles" data-section="articles">
                    <i class="fas fa-newspaper"></i> Articles
                </a>
                <a href="#comments" data-section="comments">
                    <i class="fas fa-comments"></i> Commentaires
                </a>
                <a href="#subscribers" data-section="subscribers">
                    <i class="fas fa-users"></i> Abonnés
                </a>
            </nav>
            <div class="sidebar-footer">
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i> Déconnexion
                    </button>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <div class="header">
                <h1>{{ $theme->title }}</h1>
                <p class="theme-description">{{ $theme->discription }}</p>
            </div>

            <!-- Overview Section -->
            <section id="overview" class="content-section active">
                <div class="section-header">
                    <h2>Vue d'ensemble du thème</h2>
                </div>
                <div class="overview-stats">
                    <div class="stat-card">
                        <i class="fas fa-users"></i>
                        <div class="stat-info">
                            <span class="stat-value">{{ $subscribers->count() }}</span>
                            <span class="stat-label">Abonnés</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-newspaper"></i>
                        <div class="stat-info">
                            <span class="stat-value">{{ $articles->count() }}</span>
                            <span class="stat-label">Articles</span>
                        </div>
                    </div>
                    <div class="stat-card">
                        <i class="fas fa-check-circle"></i>
                        <div class="stat-info">
                            <span class="stat-value">{{ $articles->where('status', 'Publié')->count() }}</span>
                            <span class="stat-label">Articles Publiés</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Articles Section -->
            <section id="articles" class="content-section">
                <div class="section-header">
                    <h2>Gestion des Articles</h2>
                    <div class="filters">
                        <select id="statusFilter">
                            <option value="all">Tous les statuts</option>
                            <option value="en_cours">En cours</option>
                            <option value="publie">Publié</option>
                            <option value="refuse">Refusé</option>
                            <option value="retenu">Retenu</option>
                        </select>
                    </div>
                </div>
                <div class="articles-grid">
                    @foreach($articles as $article)
                        <div class="article-card" data-status="{{ array_search($article->status, $statusMap) }}" data-user-id="{{ $article->user_id }}">
                            <div class="article-header">
                                <h3>{{ $article->title }}</h3>
                                <div class="article-meta">
                                    <span class="author">Par {{ $article->user->name }}</span>
                                </div>
                            </div>
                            <div class="article-content">
                                <p>{{ $article->content }}</p>
                            </div>
                            <div class="article-actions">
                                <select class="status-select" data-article-id="{{ $article->id }}">
                                    @foreach($statusMap as $key => $value)
                                        <option value="{{ $key }}" {{ $value === $article->status ? 'selected' : '' }}>
                                            {{ $value }}
                                        </option>
                                    @endforeach
                                </select>
                                <button class="delete-article" data-article-id="{{ $article->id }}">
                                    <i class="fas fa-trash"></i> Supprimer
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Comments Section -->
            <section id="comments" class="content-section">
                <div class="section-header">
                    <h2>Modération des Commentaires</h2>
                    <div class="filters">
                        <select id="commentStatusFilter">
                            <option value="all">Tous les commentaires</option>
                            <option value="visible">Visibles</option>
                            <option value="hidden">Masqués</option>
                        </select>
                        <select id="commentArticleFilter">
                            <option value="all">Tous les articles</option>
                            @foreach($articles as $article)
                                <option value="{{ $article->id }}">{{ $article->title }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="comments-grid">
                    @foreach($articles as $article)
                        @foreach($article->commentaires as $comment)
                            <div class="comment-card" 
                                data-article-id="{{ $article->id }}" 
                                data-visibility="{{ $comment->visibility }}"
                                data-comment-id="{{ $comment->id }}">
                                <div class="comment-header">
                                    <div class="comment-meta">
                                        <h4>{{ $article->title }}</h4>
                                        <span class="comment-author">Par {{ $comment->user->name }}</span>
                                        <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                                    </div>
                                    <div class="comment-status">
                                        <span class="status-badge {{ $comment->visibility }}">
                                            {{ $comment->visibility === 'visible' ? 'Visible' : 'Masqué' }}
                                        </span>
                                    </div>
                                </div>
                                <div class="comment-content">
                                    <p>{{ $comment->message }}</p>
                                </div>
                                <div class="comment-actions">
                                    <button class="toggle-visibility-btn" title="{{ $comment->visibility === 'visible' ? 'Masquer' : 'Afficher' }}">
                                        <i class="fas {{ $comment->visibility === 'visible' ? 'fa-eye-slash' : 'fa-eye' }}"></i>
                                        {{ $comment->visibility === 'visible' ? 'Masquer' : 'Afficher' }}
                                    </button>
                                    <button class="delete-comment-btn">
                                        <i class="fas fa-trash"></i>
                                        Supprimer
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    @endforeach
                </div>
            </section>

            <!-- Subscribers Section -->
            <section id="subscribers" class="content-section">
                <div class="section-header">
                    <h2>Gestion des Abonnés</h2>
                    <div class="search-box">
                        <input type="text" id="subscriberSearch" placeholder="Rechercher un abonné...">
                    </div>
                </div>
                <div class="subscribers-grid">
                    @foreach($subscribers as $subscriber)
                        <div class="subscriber-card">
                            <div class="subscriber-info">
                                <img src="{{ asset('images/default-avatar.png') }}" alt="Avatar" class="subscriber-avatar">
                                <div class="subscriber-details">
                                    <h3>{{ $subscriber->name }}</h3>
                                    <p>{{ $subscriber->email }}</p>
                                </div>
                            </div>
                            <div class="subscriber-stats">
                                <span>
                                    <i class="fas fa-newspaper"></i>
                                    {{ $articles->where('user_id', $subscriber->id)->count() }} articles
                                </span>
                            </div>
                            <button class="remove-subscriber" data-subscriber-id="{{ $subscriber->id }}">
                                <i class="fas fa-user-minus"></i>
                                Retirer l'abonné
                            </button>
                        </div>
                    @endforeach
                </div>
            </section>
        </div>
    </div>

    <script src="{{asset('js/theme_management/dashboard.js')}}"></script>
</body>
</html>
