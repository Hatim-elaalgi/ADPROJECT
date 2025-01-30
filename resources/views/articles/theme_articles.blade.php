<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $theme->title }} - Articles</title>
    <link rel="stylesheet" href="{{ asset('css/articles/theme_articles.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="top-nav">
            <div class="nav-content">
                <a href="{{ route('themes') }}" class="back-button">
                    <i class="fas fa-arrow-left"></i> Retour aux thèmes
                </a>
                <h1>{{ $theme->title }}</h1>
            </div>
        </nav>
    </header>

    <main>
        <div class="theme-description">
            <p>{{ $theme->discription }}</p>
        </div>

        <div class="articles-grid">
            @foreach($articles as $article)
                <article class="article-card" data-article-id="{{ $article->id }}">
                    @if($article->image_url)
                        <div class="article-image">
                            <img src="{{ asset($article->image_url) }}" alt="{{ $article->title }}">
                        </div>
                    @endif
                    <div class="article-content">
                        <h2>{{ $article->title }}</h2>
                        <div class="article-meta">
                            <span class="author">
                                <i class="fas fa-user"></i> {{ $article->user->name }}
                            </span>
                            <span class="rating">
                                <i class="fas fa-star"></i> {{ number_format($article->average_rating, 1) }}
                            </span>
                        </div>
                        <p class="article-excerpt">{{ Str::limit($article->content, 150) }}</p>
                        <a href="{{ route('articles.show', $article) }}" class="read-more">Lire la suite</a>
                    </div>
                </article>
            @endforeach
        </div>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Votre Site. Tous droits réservés.</p>
    </footer>
</body>
</html>
