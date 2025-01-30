<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use App\Models\Article;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class ThemeManagementController extends Controller
{
    private const STATUS_MAP = [
        'en_cours' => 'En cours',
        'publie' => 'Publié',
        'refuse' => 'Refus',
        'retenu' => 'Retenu'
    ];

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (Auth::user()->role !== User::ROLE_RESPONSABLE_THEME) {
                return redirect()->route('home')->with('error', 'Unauthorized access');
            }
            return $next($request);
        });
    }

    public function manageTheme(Theme $theme)
    {
        // Verify the theme belongs to the current manager
        if ($theme->user_id !== Auth::id()) {
            return redirect()->route('mes_themes')->with('error', 'Unauthorized access');
        }

        // Verify the theme is accepted by admin
        if (!$theme->is_accept) {
            return redirect()->route('mes_themes')
                ->with('error', 'Ce thème n\'est pas encore accepté par l\'administrateur');
        }

        $subscribers = $theme->users()->get();
        $articles = $theme->articles()->with(['user', 'commentaires.user'])->get();

        return view('theme_management.dashboard', [
            'theme' => $theme,
            'subscribers' => $subscribers,
            'articles' => $articles,
            'statusMap' => self::STATUS_MAP
        ]);
    }

    public function updateArticleStatus(Article $article, Request $request)
    {
        // Verify the article belongs to a theme managed by the current user
        if ($article->theme->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Verify the theme is accepted by admin
        if (!$article->theme->is_accept) {
            return response()->json(['error' => 'Ce thème n\'est pas encore accepté par l\'administrateur'], 403);
        }

        $status = $request->input('status');
        
        if (!array_key_exists($status, self::STATUS_MAP)) {
            return response()->json(['error' => 'Invalid status'], 400);
        }

        $article->status = self::STATUS_MAP[$status];
        $article->save();

        return response()->json([
            'success' => true,
            'message' => 'Article status updated successfully',
            'newStatus' => $status
        ]);
    }

    public function removeSubscriber(Theme $theme, User $subscriber)
    {
        try {
            // Start a database transaction
            DB::beginTransaction();

            // Verify the theme belongs to the current manager
            if ($theme->user_id !== Auth::id()) {
                Log::warning('Unauthorized attempt to remove subscriber', [
                    'theme_id' => $theme->id,
                    'subscriber_id' => $subscriber->id,
                    'attempted_by' => Auth::id()
                ]);
                return response()->json(['error' => 'Unauthorized'], 403);
            }

            // Verify the theme is accepted by admin
            if (!$theme->is_accept) {
                return response()->json(['error' => 'Ce thème n\'est pas encore accepté par l\'administrateur'], 403);
            }

            // Check if the user is actually subscribed
            if (!$theme->users()->where('user_id', $subscriber->id)->exists()) {
                Log::warning('Attempt to remove non-subscribed user', [
                    'theme_id' => $theme->id,
                    'subscriber_id' => $subscriber->id
                ]);
                return response()->json(['error' => 'User is not subscribed to this theme'], 404);
            }

            // Delete all articles by this subscriber in this theme
            $articlesDeleted = Article::where('theme_id', $theme->id)
                                    ->where('user_id', $subscriber->id)
                                    ->delete();

            // Remove the subscriber from the theme
            $theme->users()->detach($subscriber->id);

            // Commit the transaction
            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Subscriber and their articles removed successfully',
                'articles_deleted' => $articlesDeleted
            ]);
        } catch (\Exception $e) {
            // Rollback the transaction if anything fails
            DB::rollBack();
            return response()->json([
                'error' => 'An error occurred while removing the subscriber',
                'details' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteArticle(Article $article)
    {
        // Verify the article belongs to a theme managed by the current user
        if ($article->theme->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        // Verify the theme is accepted by admin
        if (!$article->theme->is_accept) {
            return response()->json(['error' => 'Ce thème n\'est pas encore accepté par l\'administrateur'], 403);
        }

        $article->delete();

        return response()->json([
            'success' => true,
            'message' => 'Article deleted successfully'
        ]);
    }
}
