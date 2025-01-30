<?php

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\Theme;
use App\Models\Commentaire;
use App\Models\ArticleHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriberDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = Auth::user();
        
        // Get themes the user has subscribed to using the correct relationship
        $subscribedThemes = $user->subscribedThemes()
            ->with(['themeManager', 'articles' => function($query) {
                $query->where('status', 'Publié');
            }])
            ->get()
            ->map(function($theme) {
                $theme->article_count = $theme->articles->count();
                return $theme;
            });
        
        // Get user's article history
        $articleHistory = ArticleHistory::where('user_id', $user->id)
            ->with(['article.theme'])
            ->orderBy('last_visited_at', 'desc')
            ->take(10)
            ->get();
        
        // Get user's proposed articles
        $proposedArticles = Article::where('user_id', $user->id)
            ->with('theme')
            ->orderBy('created_at', 'desc')
            ->get();
        
        // Get user's comments
        $comments = Commentaire::where('user_id', $user->id)
            ->with(['article.theme'])
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('subscriber.dashboard', [
            'subscribedThemes' => $subscribedThemes,
            'articleHistory' => $articleHistory,
            'proposedArticles' => $proposedArticles,
            'comments' => $comments
        ]);
    }

    public function proposeArticle(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'theme_id' => 'required|exists:themes,id',
            'image' => 'nullable|image|max:2048'
        ]);

        // Verify user is subscribed to the theme using the correct relationship
        $user = Auth::user();
        $theme = Theme::find($request->theme_id);
        
        if (!$user->subscribedThemes->contains($theme)) {
            return redirect()->back()->with('error', 'Vous devez être abonné au thème pour proposer un article');
        }

        $article = new Article();
        $article->title = $request->title;
        $article->content = $request->content;
        $article->theme_id = $request->theme_id;
        $article->user_id = Auth::id();
        $article->status = 'En cours';

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('articles', 'public');
            $article->image_url = 'storage/' . $path;
        }

        $article->save();

        return redirect()->back()->with('success', 'Article proposé avec succès');
    }

    public function deleteComment(Commentaire $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $comment->delete();

        return response()->json([
            'success' => true,
            'message' => 'Commentaire supprimé'
        ]);
    }
}
