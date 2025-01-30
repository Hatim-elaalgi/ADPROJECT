<?php

namespace App\Http\Controllers;

use App\Models\Commentaire;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function toggleVisibility(Request $request, Commentaire $comment)
    {
        // Check if user is theme manager
        $article = Article::findOrFail($comment->article_id);
        $theme = $article->theme;
        
        if ($theme->user_id !== Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Non autorisé'
            ], 403);
        }

        $comment->visibility = $comment->visibility === 'visible' ? 'hidden' : 'visible';
        $comment->save();

        return response()->json([
            'success' => true,
            'visibility' => $comment->visibility,
            'message' => $comment->visibility === 'visible' ? 'Commentaire affiché' : 'Commentaire masqué'
        ]);
    }

    public function delete(Request $request, Commentaire $comment)
    {
        // Check if user is theme manager
        $article = Article::findOrFail($comment->article_id);
        $theme = $article->theme;
        
        if ($theme->user_id !== Auth::id()) {
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
