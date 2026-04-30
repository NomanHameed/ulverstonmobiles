<?php

namespace App\Providers;

use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        FileUpload::configureUsing(fn (FileUpload $upload) => $upload
            ->disk('public')
            ->visibility('public'));

        ImageColumn::configureUsing(fn (ImageColumn $column) => $column->disk('public'));
    }
}
