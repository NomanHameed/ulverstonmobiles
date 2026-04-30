<?php

namespace App\Http\Middleware;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Inertia\Middleware;

class HandleInertiaRequests extends Middleware
{
    protected $rootView = 'app';

    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    public function share(Request $request): array
    {
        return [
            ...parent::share($request),

            'app' => [
                'name' => config('app.name'),
            ],

            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error'   => fn () => $request->session()->get('error'),
            ],

            'site' => fn () => [
                'phone'     => SiteSetting::get('company.phone'),
                'whatsapp'  => SiteSetting::get('company.whatsapp'),
                'email'     => SiteSetting::get('company.email'),
                'address'   => SiteSetting::get('company.address'),
                'hours'     => SiteSetting::get('company.hours'),
                'instagram' => SiteSetting::get('social.instagram'),
                'tiktok'    => SiteSetting::get('social.tiktok'),
            ],

            'announcement' => fn () => Announcement::query()
                ->live()
                ->latest()
                ->first()
                ?->only([
                    'id', 'message', 'link_url', 'link_label',
                    'background_color', 'text_color', 'is_dismissible',
                ]),

            'menuCategories' => fn () => Category::query()
                ->where('is_active', true)
                ->whereNull('parent_id')
                ->orderBy('sort_order')
                ->get(['id', 'name', 'slug', 'icon', 'description', 'is_featured'])
                ->toArray(),
        ];
    }
}
