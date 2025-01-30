<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Theme;
use App\Models\Rating;
use App\Models\Commentaire;
use App\Models\ArticleHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
    public function themeArticles(Theme $theme)
    {
        $articles = $theme->articles()
            ->where('status', 'Publié')
            ->with(['user', 'ratings'])
            ->get()
            ->map(function ($article) {
                $article->average_rating = $article->ratings->avg('rating') ?? 0;
                return $article;
            });

        return view('articles.theme_articles', [
            'theme' => $theme,
            'articles' => $articles
        ]);
    }

    public function show(Article $article)
    {
        // Check if user is theme manager or subscribed to the theme
        if ($article->theme->user_id !== Auth::id() && !$article->theme->users->contains(Auth::id())) {
            return redirect()->route('themes')->with('error', 'Vous devez être abonné au thème pour voir cet article');
        }

        // Record article view in history
        if (Auth::check()) {
            ArticleHistory::updateOrCreate(
                [
                    'user_id' => Auth::id(),
                    'article_id' => $article->id
                ],
                [
                    'last_visited_at' => now()
                ]
            );
        }

        $userRating = $article->ratings()->where('user_id', Auth::id())->value('rating') ?? 0;
        $averageRating = $article->ratings()->avg('rating') ?? 0;

        return view('articles.show', [
            'article' => $article,
            'userRating' => $userRating,
            'averageRating' => $averageRating
        ]);
    }

    public function rate(Request $request, Article $article)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5'
        ]);

        $rating = Rating::updateOrCreate(
            [
                'article_id' => $article->id,
                'user_id' => Auth::id()
            ],
            ['rating' => $request->rating]
        );

        $averageRating = Rating::where('article_id', $article->id)->avg('rating');

        return response()->json([
            'success' => true,
            'average_rating' => round($averageRating, 1)
        ]);
    }

    public function comment(Request $request, Article $article)
    {
        $request->validate([
            'message' => 'required|string|max:1000'
        ]);

        $comment = new Commentaire([
            'message' => $request->message,
            'user_id' => Auth::id()
        ]);

        $article->commentaires()->save($comment);

        return response()->json([
            'success' => true,
            'comment' => [
                'id' => $comment->id,
                'message' => $comment->message,
                'user_name' => Auth::user()->name,
                'created_at' => $comment->created_at->diffForHumans()
            ]
        ]);
    }
}
