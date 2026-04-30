<?php

namespace App\Http\Controllers;

use App\Models\RepairBrand;
use App\Models\RepairIssue;
use Inertia\Inertia;
use Inertia\Response;

class RepairController extends Controller
{
    public function __invoke(): Response
    {
        $brands = RepairBrand::query()
            ->where('is_active', true)
            ->with(['models' => fn ($q) => $q->where('is_active', true)->orderBy('sort_order')])
            ->orderBy('sort_order')
            ->get()
            ->map(fn (RepairBrand $b) => [
                'id'     => $b->id,
                'name'   => $b->name,
                'slug'   => $b->slug,
                'logo'   => $b->logo_path,
                'models' => $b->models->map(fn ($m) => [
                    'id'    => $m->id,
                    'name'  => $m->name,
                    'slug'  => $m->slug,
                    'image' => $m->image_path,
                    'release_year' => $m->release_year,
                ]),
            ]);

        $issues = RepairIssue::where('is_active', true)
            ->orderBy('sort_order')
            ->get()
            ->map(fn (RepairIssue $i) => [
                'id'                => $i->id,
                'name'              => $i->name,
                'slug'              => $i->slug,
                'description'       => $i->description,
                'icon'              => $i->icon,
                'base_price'        => $i->base_price ? (float) $i->base_price : null,
                'estimated_minutes' => $i->estimated_minutes,
            ]);

        return Inertia::render('Repair/Wizard', [
            'brands' => $brands,
            'issues' => $issues,
        ]);
    }
}
