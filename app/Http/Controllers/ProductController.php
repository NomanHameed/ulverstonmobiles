<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class ProductController extends Controller
{
    public function index(Request $request): Response
    {
        $brand    = $request->query('brand');
        $category = $request->query('category');
        $featured = $request->boolean('featured');
        $sort     = $request->query('sort', 'newest');

        $query = Product::published()
            ->with(['brand:id,name,slug', 'category:id,name,slug', 'primaryImage:id,product_id,path']);

        if ($brand) {
            $query->whereHas('brand', fn ($q) => $q->where('slug', $brand));
        }

        if ($category) {
            $query->whereHas('category', fn ($q) => $q->where('slug', $category));
        }

        if ($featured) {
            $query->featured();
        }

        match ($sort) {
            'name'       => $query->orderBy('name'),
            default      => $query->orderByDesc('published_at'),
        };

        $products = $query->paginate(12)->withQueryString()->through(fn (Product $p) => [
            'id'                => $p->id,
            'name'              => $p->name,
            'slug'              => $p->slug,
            'short_description' => $p->short_description,
            'brand_name'        => $p->brand?->name,
            'category_name'     => $p->category?->name,
            'primary_image'     => $p->primaryImage?->path,
        ]);

        return Inertia::render('Products/Index', [
            'products'  => $products,
            'filters'   => [
                'brand'    => $brand,
                'category' => $category,
                'featured' => $featured,
                'sort'     => $sort,
            ],
            'brands'     => Brand::where('is_active', true)->orderBy('name')->get(['id', 'name', 'slug']),
            'categories' => Category::where('is_active', true)->whereNull('parent_id')->orderBy('sort_order')->get(['id', 'name', 'slug']),
        ]);
    }

    public function show(Product $product): Response
    {
        abort_unless($product->is_published, 404);

        $product->load([
            'brand:id,name,slug',
            'category:id,name,slug',
            'images',
            'variants' => fn ($q) => $q->where('is_active', true),
            'features',
        ]);

        return Inertia::render('Products/Show', [
            'product' => [
                'id'                => $product->id,
                'name'              => $product->name,
                'slug'              => $product->slug,
                'short_description' => $product->short_description,
                'description'       => $product->description,
                'stock_status'      => $product->stock_status,
                'sku'               => $product->sku,
                'meta_title'        => $product->meta_title,
                'meta_description'  => $product->meta_description,
                'brand'             => $product->brand,
                'category'          => $product->category,
                'images'            => $product->images->map(fn ($i) => [
                    'id'      => $i->id,
                    'path'    => $i->path,
                    'alt'     => $i->alt,
                    'is_primary' => $i->is_primary,
                ]),
                'variants'          => $product->variants->map(fn ($v) => [
                    'id'             => $v->id,
                    'color'          => $v->color,
                    'color_hex'      => $v->color_hex,
                    'storage'        => $v->storage,
                    'label'          => $v->label,
                    'stock'          => $v->stock,
                    'is_default'     => $v->is_default,
                ]),
                'features'          => $product->features->map(fn ($f) => [
                    'id'          => $f->id,
                    'title'       => $f->title,
                    'description' => $f->description,
                    'icon'        => $f->icon,
                    'image_path'  => $f->image_path,
                ]),
            ],
        ]);
    }
}
