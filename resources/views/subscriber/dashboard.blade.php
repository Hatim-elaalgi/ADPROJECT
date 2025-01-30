<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tableau de bord - Abonné</title>
    <link rel="stylesheet" href="{{ asset('css/subscriber/dashboard.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="dashboard-container">
        <aside class="sidebar">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user-circle"></i>
                </div>
                <h2>{{ Auth::user()->name }}</h2>
                <span class="role">Abonné</span>
            </div>

            <nav class="sidebar-nav">
                <a href="#themes" class="active" data-section="themes">
                    <i class="fas fa-bookmark"></i> Mes Thèmes
                </a>
                <a href="#propose-theme" data-section="propose-theme">
                    <i class="fas fa-lightbulb"></i> Proposer un Thème
                </a>
                <a href="#history" data-section="history">
                    <i class="fas fa-history"></i> Historique
                </a>
                <a href="#propose" data-section="propose">
                    <i class="fas fa-pen"></i> Proposer un Article
                </a>
                <a href="#my-articles" data-section="my-articles">
                    <i class="fas fa-newspaper"></i> Mes Articles
                </a>
                <a href="#comments" data-section="comments">
                    <i class="fas fa-comments"></i> Mes Commentaires
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <!-- Themes Section -->
            <section id="themes" class="content-section active">
                <div class="section-header">
                    <h2>Mes Thèmes Suivis</h2>
                    <a href="#propose-theme" class="btn btn-primary section-link">
                        <i class="fas fa-lightbulb me-2"></i>Proposer un thème
                    </a>
                </div>
                <div class="themes-grid">
                    @foreach($subscribedThemes as $theme)
                        <div class="theme-card">
                            <div class="theme-header">
                                <h3>{{ $theme->title }}</h3>
                                <span class="manager">Par {{ $theme->themeManager->name }}</span>
                            </div>
                            <p class="theme-description">{{ $theme->description }}</p>
                            <div class="theme-stats">
                                <span class="stat">
                                    <i class="fas fa-newspaper"></i>
                                    {{ $theme->article_count }} articles publiés
                                </span>
                            </div>
                            <div class="button-group">
                                <a href="{{ route('articles.theme', $theme) }}" class="btn">
                                    <i class="fas fa-book-reader"></i>
                                    <span>Articles</span>
                                </a>
                                <form action="{{ route('themes.unsubscribe', $theme->id) }}" method="POST" class="inline-form">
                                    @csrf
                                    <button type="submit" class="btn-danger">
                                        <i class="fas fa-user-minus"></i>
                                        <span>Désabonner</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endforeach

                    @if($subscribedThemes->isEmpty())
                        <div class="empty-state">
                            <i class="fas fa-bookmark"></i>
                            <p>Vous n'êtes abonné à aucun thème pour le moment.</p>
                            <a href="{{ route('themes') }}" class="btn">
                                <i class="fas fa-search"></i> Découvrir des thèmes
                            </a>
                        </div>
                    @endif
                </div>
            </section>

            <!-- Add new Propose Theme Section -->
            <section id="propose-theme" class="content-section">
                <div class="section-header">
                    <h2>Proposer un Nouveau Thème</h2>
                </div>
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('subscriber.themes.propose') }}" method="POST">
                            @csrf
                            <div class="form-group mb-3">
                                <label for="title" class="form-label">Titre du thème</label>
                                <input type="text" class="form-control @error('title') is-invalid @enderror" 
                                       id="title" name="title" value="{{ old('title') }}" required>
                                @error('title')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="form-label">Description</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="4" required>{{ old('description') }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="alert alert-info mb-3">
                                <i class="fas fa-info-circle me-2"></i>
                                En proposant un thème, vous deviendrez responsable de ce thème une fois qu'il sera accepté par l'administrateur.
                            </div>

                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-paper-plane me-2"></i>Soumettre la proposition
                            </button>
                        </form>
                    </div>
                </div>
            </section>

            <!-- History Section -->
            <section id="history" class="content-section">
                <div class="section-header">
                    <h2>Historique de Lecture</h2>
                </div>
                <div class="history-list">
                    @foreach($articleHistory as $history)
                        <div class="history-item">
                            <div class="history-content">
                                <h3>{{ $history->article->title }}</h3>
                                <span class="theme-name">{{ $history->article->theme->title }}</span>
                                <span class="visit-date">Lu le {{ $history->last_visited_at->format('d/m/Y H:i') }}</span>
                            </div>
                            <a href="{{ route('articles.show', $history->article) }}" class="btn-link">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Propose Article Section -->
            <section id="propose" class="content-section">
                <div class="section-header">
                    <h2>Proposer un Article</h2>
                </div>
                <form action="{{ route('subscriber.propose-article') }}" method="POST" enctype="multipart/form-data" class="article-form">
                    @csrf
                    <div class="form-group">
                        <label for="theme">Thème</label>
                        <select name="theme_id" id="theme" required>
                            @foreach($subscribedThemes as $theme)
                                <option value="{{ $theme->id }}">{{ $theme->title }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="title">Titre</label>
                        <input type="text" name="title" id="title" required>
                    </div>
                    <div class="form-group">
                        <label for="content">Contenu</label>
                        <textarea name="content" id="content" rows="10" required></textarea>
                    </div>
                    <div class="form-group">
                        <label for="image">Image (optionnel)</label>
                        <input type="file" name="image" id="image" accept="image/*">
                    </div>
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-paper-plane"></i> Proposer l'article
                    </button>
                </form>
            </section>

            <!-- My Articles Section -->
            <section id="my-articles" class="content-section">
                <div class="section-header">
                    <h2>Mes Articles Proposés</h2>
                </div>
                <div class="articles-list">
                    @foreach($proposedArticles as $article)
                        <div class="article-card">
                            <div class="article-info">
                                <h3>{{ $article->title }}</h3>
                                <span class="theme-name">{{ $article->theme->title }}</span>
                                <span class="status {{ strtolower($article->status) }}">{{ $article->status }}</span>
                            </div>
                            <div class="article-meta">
                                <span class="date">Proposé le {{ $article->created_at->format('d/m/Y') }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>

            <!-- Comments Section -->
            <section id="comments" class="content-section">
                <div class="section-header">
                    <h2>Mes Commentaires</h2>
                </div>
                <div class="comments-list">
                    @foreach($comments as $comment)
                        <div class="comment-card" data-comment-id="{{ $comment->id }}">
                            <div class="comment-header">
                                <div class="comment-meta">
                                    <h4>{{ $comment->article->title }}</h4>
                                    <span class="theme-name">{{ $comment->article->theme->title }}</span>
                                </div>
                                <button class="delete-comment-btn" title="Supprimer">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </div>
                            <div class="comment-content">
                                <p>{{ $comment->message }}</p>
                            </div>
                            <div class="comment-footer">
                                <span class="date">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        </main>
    </div>

    <script src="{{ asset('js/shared/notifications.js') }}"></script>
    <script src="{{ asset('js/subscriber/dashboard.js') }}"></script>
</body>
</html>
