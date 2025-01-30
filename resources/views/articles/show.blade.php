<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $article->title }}</title>
    <link rel="stylesheet" href="{{ asset('css/articles/show.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
    <header>
        <nav class="top-nav">
            <div class="nav-content">
                <a href="{{ route('articles.theme', $article->theme) }}" class="back-button">
                    <i class="fas fa-arrow-left"></i> Retour aux articles
                </a>
            </div>
        </nav>
    </header>

    <main>
        <article class="article-full">
            <div class="article-header">
                <h1>{{ $article->title }}</h1>
                <div class="article-meta">
                    <span class="author">
                        <i class="fas fa-user"></i> {{ $article->user->name }}
                    </span>
                    <span class="date">
                        <i class="fas fa-calendar"></i> {{ $article->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            @if($article->image_url)
                <div class="article-image">
                    <img src="{{ asset($article->image_url) }}" alt="{{ $article->title }}">
                </div>
            @endif

            <div class="article-content">
                {{ $article->content }}
            </div>

            <div class="rating-section" data-article-id="{{ $article->id }}">
                <h3>Noter cet article</h3>
                <div class="stars">
                    @for($i = 1; $i <= 5; $i++)
                        <i class="star fas fa-star {{ $i <= $userRating ? 'active' : '' }}" data-rating="{{ $i }}"></i>
                    @endfor
                </div>
                <div class="average-rating">
                    Note moyenne : <span>{{ number_format($averageRating, 1) }}</span>/5
                </div>
            </div>

            <div class="comments-section">
                <h3>Commentaires</h3>
                @auth
                    <form id="comment-form" class="comment-form" data-article-id="{{ $article->id }}">
                        <textarea name="message" placeholder="Votre commentaire..." required></textarea>
                        <button type="submit">Commenter</button>
                    </form>
                @else
                    <p class="login-prompt">
                        <a href="{{ route('login') }}">Connectez-vous</a> pour laisser un commentaire.
                    </p>
                @endauth

                <div class="comments-list">
                    @foreach($article->commentaires->sortByDesc('created_at') as $comment)
                        <div class="comment" data-comment-id="{{ $comment->id }}">
                            <div class="comment-header">
                                <span class="comment-author">{{ $comment->user->name }}</span>
                                <span class="comment-date">{{ $comment->created_at->diffForHumans() }}</span>
                            </div>
                            <div class="comment-content {{ $comment->visibility === 'hidden' ? 'hidden' : '' }}">
                                {{ $comment->message }}
                            </div>
                            @if($comment->visibility === 'hidden')
                                <div class="hidden-message">
                                    <i class="fas fa-eye-slash"></i> Ce commentaire est masqué
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </article>
    </main>

    <footer>
        <p>&copy; {{ date('Y') }} Votre Site. Tous droits réservés.</p>
    </footer>

    <script src="{{ asset('js/articles/show.js') }}"></script>
    <script src="{{ asset('js/shared/notifications.js') }}"></script>
</body>
</html>
