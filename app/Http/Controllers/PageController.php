<?php

namespace App\Http\Controllers;

use App\Models\DiscountProgram;
use App\Models\GalleryImage;
use App\Models\Page;
use Inertia\Inertia;
use Inertia\Response;

class PageController extends Controller
{
    public function about(): Response
    {
        $page = Page::published()->where('slug', 'about')->first();

        return Inertia::render('About', [
            'page' => $page ? $this->serializePage($page) : null,
            'gallery' => GalleryImage::forGroup('store')->get(['id', 'image_path', 'caption', 'alt']),
        ]);
    }

    public function contact(): Response
    {
        $page = Page::published()->where('slug', 'contact')->first();

        return Inertia::render('Contact', [
            'page' => $page ? $this->serializePage($page) : null,
        ]);
    }

    public function discounts(): Response
    {
        $page = Page::published()->where('slug', 'discounts')->first();

        $programs = DiscountProgram::active()->get([
            'id', 'name', 'slug', 'eligibility', 'description', 'icon', 'cta_label', 'cta_url',
        ]);

        return Inertia::render('Discounts', [
            'page'     => $page ? $this->serializePage($page) : null,
            'programs' => $programs,
        ]);
    }

    protected function serializePage(Page $page): array
    {
        return [
            'slug'             => $page->slug,
            'title'            => $page->title,
            'eyebrow'          => $page->eyebrow,
            'intro'            => $page->intro,
            'body'             => $page->body,
            'hero_image_path'  => $page->hero_image_path,
            'meta_title'       => $page->meta_title,
            'meta_description' => $page->meta_description,
        ];
    }
}
