<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class VoiceActionsController extends Controller
{
    /**
     * Get voice commands for Voice Actions SDK
     * Supports demo mode and platform-specific commands
     */
    public function getCommands(Request $request): JsonResponse
    {
        $locale = $request->get('locale', 'en-US');
        $platform = $request->get('platform_name', 'custom');
        
        // Base commands that work for all platforms
        $baseCommands = [
            [
                'id' => 'scroll-down',
                'action' => 'scroll-down',
                'phrases' => ['scroll down', 'scroll down page', 'go down', 'move down', 'page down', 'scroll down more'],
                'category' => 'navigation'
            ],
            [
                'id' => 'scroll-up',
                'action' => 'scroll-up',
                'phrases' => ['scroll up', 'scroll up page', 'go up', 'move up', 'page up', 'scroll up more'],
                'category' => 'navigation'
            ],
            [
                'id' => 'scroll-to-top',
                'action' => 'scroll-to-top',
                'phrases' => ['scroll to top', 'go to top', 'top of page', 'beginning', 'top', 'scroll top'],
                'category' => 'navigation'
            ],
            [
                'id' => 'scroll-to-bottom',
                'action' => 'scroll-to-bottom',
                'phrases' => ['scroll to bottom', 'go to bottom', 'end of page', 'bottom', 'scroll bottom', 'end'],
                'category' => 'navigation'
            ],
            [
                'id' => 'search',
                'action' => 'search',
                'phrases' => ['search', 'open search', 'focus search', 'show search', 'search box'],
                'category' => 'navigation'
            ]
        ];
        
        // Platform-specific commands for stargate-ci
        $platformCommands = [];
        if ($platform === 'stargate-ci') {
            $platformCommands = [
                [
                    'id' => 'navigate-home',
                    'action' => 'navigate-home',
                    'phrases' => ['go to home', 'home page', 'home', 'main page', 'go home', 'take me home', 'homepage'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-events',
                    'action' => 'navigate-events',
                    'phrases' => ['go to events', 'events page', 'events', 'show events', 'view events', 'upcoming events', 'event page'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-news',
                    'action' => 'navigate-news',
                    'phrases' => ['go to news', 'news page', 'news', 'show news', 'latest news', 'read news', 'news articles'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-about',
                    'action' => 'navigate-about',
                    'phrases' => ['go to about', 'about page', 'about', 'about us', 'show about', 'tell me about', 'about section'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-faq',
                    'action' => 'navigate-faq',
                    'phrases' => ['go to faq', 'faq page', 'faq', 'frequently asked questions', 'show faq', 'help', 'questions'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-contact',
                    'action' => 'navigate-contact',
                    'phrases' => ['go to contact', 'contact page', 'contact', 'contact us', 'get in touch', 'contact page'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-subscribe',
                    'action' => 'navigate-subscribe',
                    'phrases' => ['go to subscribe', 'subscribe', 'subscribe page', 'sign up for updates', 'subscribe to updates', 'newsletter'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-search',
                    'action' => 'navigate-search',
                    'phrases' => ['go to search', 'search page', 'open search page', 'show search page'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-signin',
                    'action' => 'navigate-signin',
                    'phrases' => ['go to sign in', 'sign in page', 'sign in', 'login', 'log in', 'sign in to account', 'login page'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-signup',
                    'action' => 'navigate-signup',
                    'phrases' => ['go to sign up', 'sign up page', 'sign up', 'register', 'create account', 'new account', 'registration'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-privacy',
                    'action' => 'navigate-privacy',
                    'phrases' => ['go to privacy', 'privacy policy', 'privacy page', 'show privacy', 'privacy settings'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-terms',
                    'action' => 'navigate-terms',
                    'phrases' => ['go to terms', 'terms of service', 'terms page', 'show terms', 'terms and conditions'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-cookies',
                    'action' => 'navigate-cookies',
                    'phrases' => ['go to cookies', 'cookie policy', 'cookies page', 'show cookies', 'cookie settings'],
                    'category' => 'navigation'
                ],
                [
                    'id' => 'navigate-settings',
                    'action' => 'navigate-settings',
                    'phrases' => ['go to settings', 'settings page', 'settings', 'open settings', 'show settings', 'preferences'],
                    'category' => 'navigation'
                ]
            ];
        }
        
        // Combine base and platform commands
        $commands = array_merge($baseCommands, $platformCommands);
        
        return response()->json([
            'success' => true,
            'commands' => $commands,
            'locale' => $locale,
            'platform' => $platform,
            'count' => count($commands)
        ]);
    }
    
    /**
     * Demo endpoint for Voice Actions SDK (no API key required)
     */
    public function getDemoCommands(Request $request): JsonResponse
    {
        return $this->getCommands($request);
    }
}

