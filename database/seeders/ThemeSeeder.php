<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Theme;
use App\Models\Article;
use Illuminate\Support\Facades\Hash;

class ThemeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create theme managers
        $themeManagers = [];
        for ($i = 1; $i <= 3; $i++) {
            $themeManagers[] = User::create([
                'name' => "Theme Manager $i",
                'email' => "manager$i@example.com",
                'password' => Hash::make('password'),
                'role' => User::ROLE_RESPONSABLE_THEME
            ]);
        }

        // Create subscribers
        $subscribers = [];
        for ($i = 1; $i <= 10; $i++) {
            $subscribers[] = User::create([
                'name' => "Subscriber $i",
                'email' => "subscriber$i@example.com",
                'password' => Hash::make('password'),
                'role' => User::ROLE_SUBSCRIBER
            ]);
        }

        // Theme topics and their descriptions
        $themeTopics = [
            'Technology' => 'Latest news and trends in technology',
            'Science' => 'Scientific discoveries and research',
            'Health' => 'Health and wellness information',
            'Environment' => 'Environmental issues and sustainability',
            'Education' => 'Educational resources and trends',
            'Business' => 'Business news and strategies',
        ];

        // Create themes and assign to managers
        foreach ($themeTopics as $title => $description) {
            $theme = Theme::create([
                'title' => $title,
                'discription' => $description,
                'is_accept' => true,
                'user_id' => $themeManagers[array_rand($themeManagers)]->id
            ]);

            // Add 3-7 random subscribers to each theme
            $numSubscribers = rand(3, 7);
            $themeSubscribers = [];
            $shuffledSubscribers = $subscribers;
            shuffle($shuffledSubscribers);
            
            for ($i = 0; $i < $numSubscribers && $i < count($shuffledSubscribers); $i++) {
                $themeSubscribers[] = $shuffledSubscribers[$i];
            }
            
            // Attach the selected subscribers to the theme
            $theme->users()->attach(array_column($themeSubscribers, 'id'));

            // Add 5-10 articles for each theme, but only from subscribed users
            $articleStatuses = ['En cours', 'Publié', 'Refus', 'Retenu'];
            $numArticles = rand(5, 10);
            
            for ($i = 1; $i <= $numArticles; $i++) {
                // Pick a random subscribed user for this article
                $randomSubscriber = $themeSubscribers[array_rand($themeSubscribers)];
                
                Article::create([
                    'title' => "$title Article $i",
                    'content' => "This is a detailed article about $title. It contains important information and insights about the topic.",
                    'status' => $articleStatuses[array_rand($articleStatuses)],
                    'user_id' => $randomSubscriber->id,
                    'theme_id' => $theme->id
                ]);
            }
        }
    }
}
