<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Theme;
use App\Models\Article;

class ThemeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_removing_subscriber_only_deletes_articles_in_specific_theme()
    {
        // Create a theme manager
        $themeManager = User::create([
            'name' => 'Theme Manager',
            'email' => 'manager@test.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_RESPONSABLE_THEME
        ]);

        // Create a subscriber
        $subscriber = User::create([
            'name' => 'Test Subscriber',
            'email' => 'subscriber@test.com',
            'password' => bcrypt('password'),
            'role' => User::ROLE_SUBSCRIBER
        ]);

        // Create two themes managed by the same manager
        $theme1 = Theme::create([
            'title' => 'Theme 1',
            'discription' => 'Test Theme 1',
            'is_accept' => true,
            'user_id' => $themeManager->id
        ]);

        $theme2 = Theme::create([
            'title' => 'Theme 2',
            'discription' => 'Test Theme 2',
            'is_accept' => true,
            'user_id' => $themeManager->id
        ]);

        // Subscribe the user to both themes
        $theme1->users()->attach($subscriber->id);
        $theme2->users()->attach($subscriber->id);

        // Create articles in both themes
        $article1InTheme1 = Article::create([
            'title' => 'Article 1 in Theme 1',
            'content' => 'Content 1',
            'status' => 'En cours',
            'user_id' => $subscriber->id,
            'theme_id' => $theme1->id
        ]);

        $article2InTheme1 = Article::create([
            'title' => 'Article 2 in Theme 1',
            'content' => 'Content 2',
            'status' => 'Publié',
            'user_id' => $subscriber->id,
            'theme_id' => $theme1->id
        ]);

        $article1InTheme2 = Article::create([
            'title' => 'Article 1 in Theme 2',
            'content' => 'Content 3',
            'status' => 'En cours',
            'user_id' => $subscriber->id,
            'theme_id' => $theme2->id
        ]);

        $article2InTheme2 = Article::create([
            'title' => 'Article 2 in Theme 2',
            'content' => 'Content 4',
            'status' => 'Publié',
            'user_id' => $subscriber->id,
            'theme_id' => $theme2->id
        ]);

        // Act as the theme manager
        $this->actingAs($themeManager);

        // Remove subscriber from Theme 1
        $response = $this->delete("/themes/{$theme1->id}/subscribers/{$subscriber->id}");

        // Assert the response is successful
        $response->assertStatus(200);

        // Assert articles in Theme 1 are deleted
        $this->assertDatabaseMissing('articles', ['id' => $article1InTheme1->id]);
        $this->assertDatabaseMissing('articles', ['id' => $article2InTheme1->id]);

        // Assert articles in Theme 2 still exist
        $this->assertDatabaseHas('articles', ['id' => $article1InTheme2->id]);
        $this->assertDatabaseHas('articles', ['id' => $article2InTheme2->id]);

        // Assert subscriber is removed from Theme 1 but still in Theme 2
        $this->assertDatabaseMissing('themes_users_', [
            'theme_id' => $theme1->id,
            'user_id' => $subscriber->id
        ]);
        $this->assertDatabaseHas('themes_users_', [
            'theme_id' => $theme2->id,
            'user_id' => $subscriber->id
        ]);
    }
}
