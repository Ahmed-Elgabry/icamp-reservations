<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Mpdf\Mpdf;

class PdfServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind('mpdf', function ($app, $config = []) {
            $defaultConfig = [
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_header' => 10,
                'margin_footer' => 10,
                'default_font' => 'cairo',
                'tempDir' => storage_path('app/mpdf')
            ];

            $config = array_merge($defaultConfig, $config);

            return new Mpdf($config);
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
