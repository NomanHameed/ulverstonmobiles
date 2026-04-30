<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\GalleryImage;
use App\Models\HeroSlide;
use App\Models\Page;
use App\Models\Product;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    public function __invoke(): Response
    {
        $slides = HeroSlide::live()->get([
            'id', 'eyebrow', 'headline', 'subheadline',
            'image_path', 'mobile_image_path',
            'primary_cta_label', 'primary_cta_url',
            'secondary_cta_label', 'secondary_cta_url',
            'theme', 'text_align',
        ]);

        $brands = Brand::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get(['id', 'name', 'slug', 'logo_path']);

        $featured = Product::published()->featured()
            ->with(['brand:id,name', 'primaryImage:id,product_id,path'])
            ->take(8)
            ->get()
            ->map(fn (Product $p) => [
                'id'                => $p->id,
                'name'              => $p->name,
                'slug'              => $p->slug,
                'short_description' => $p->short_description,
                'base_price'        => (float) $p->base_price,
                'sale_price'        => $p->sale_price ? (float) $p->sale_price : null,
                'current_price'     => $p->current_price,
                'is_on_sale'        => $p->is_on_sale,
                'currency'          => $p->currency,
                'brand_name'        => $p->brand?->name,
                'primary_image'     => $p->primaryImage?->path,
            ]);

        $spotlights = Product::published()
            ->with(['brand:id,name', 'features', 'primaryImage:id,product_id,path'])
            ->where('is_featured', true)
            ->orderByDesc('updated_at')
            ->take(3)
            ->get()
            ->map(fn (Product $p) => [
                'id'                => $p->id,
                'name'              => $p->name,
                'slug'              => $p->slug,
                'short_description' => $p->short_description,
                'brand_name'        => $p->brand?->name,
                'primary_image'     => $p->primaryImage?->path,
                'current_price'     => $p->current_price,
                'currency'          => $p->currency,
                'features'          => $p->features->take(4)->map(fn ($f) => [
                    'id'          => $f->id,
                    'title'       => $f->title,
                    'description' => $f->description,
                    'icon'        => $f->icon,
                ]),
            ]);

        $storeGallery = GalleryImage::forGroup('store')->get([
            'id', 'image_path', 'caption', 'alt',
        ]);

        $repairGallery = GalleryImage::forGroup('repair')->get([
            'id', 'image_path', 'caption', 'alt',
        ]);

        $about = Page::published()->where('slug', 'about')->first();

        return Inertia::render('Home', [
            'slides'        => $slides,
            'brands'        => $brands,
            'featured'      => $featured,
            'spotlights'    => $spotlights,
            'storeGallery'  => $storeGallery,
            'repairGallery' => $repairGallery,
            'about'         => $about ? [
                'title'   => $about->title,
                'eyebrow' => $about->eyebrow,
                'intro'   => $about->intro,
            ] : null,
        ]);
    }
}
