<?php

namespace App\Filament\Widgets;

use App\Models\Inquiry;
use App\Models\Product;
use App\Models\ProductVariant;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class InquiryPipelineStats extends BaseWidget
{
    protected static ?int $sort = 1;
    protected int|string|array $columnSpan = 'full';

    protected function getStats(): array
    {
        $newInquiries = Inquiry::where('status', 'new')->count();
        $openInquiries = Inquiry::open()->count();
        $bookedThisWeek = Inquiry::where('status', 'booked')
            ->whereBetween('booked_at', [now()->startOfWeek(), now()->endOfWeek()])
            ->count();
        $publishedProducts = Product::published()->count();
        $lowStockVariants  = ProductVariant::where('is_active', true)
            ->where('stock', '<=', 3)
            ->count();

        return [
            Stat::make('New inquiries', $newInquiries)
                ->description('Awaiting first contact')
                ->descriptionIcon('heroicon-m-bell-alert')
                ->color($newInquiries > 0 ? 'warning' : 'gray'),

            Stat::make('Open pipeline', $openInquiries)
                ->description('Not yet completed or cancelled')
                ->descriptionIcon('heroicon-m-arrow-trending-up')
                ->color('primary'),

            Stat::make('Booked this week', $bookedThisWeek)
                ->description('Repairs & orders booked')
                ->descriptionIcon('heroicon-m-calendar-days')
                ->color('info'),

            Stat::make('Published products', $publishedProducts)
                ->description('Live on storefront')
                ->descriptionIcon('heroicon-m-cube')
                ->color('success'),

            Stat::make('Low stock variants', $lowStockVariants)
                ->description('≤ 3 units remaining')
                ->descriptionIcon('heroicon-m-exclamation-triangle')
                ->color($lowStockVariants > 0 ? 'danger' : 'gray'),
        ];
    }
}
