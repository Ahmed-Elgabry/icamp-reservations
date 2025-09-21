<?php

namespace App\Providers;

use App\Models\User;
use App\Models\Banner;
use App\Models\Category;
use App\Models\InitialPage;
use App\Models\TermsSittng;
use Illuminate\Http\Request;
use App\Observers\UserObserver;
use App\Observers\BannerObserver;
use App\Observers\CategoryObserver;
use Illuminate\Support\Facades\App;
use Illuminate\Pagination\Paginator;
use App\Observers\InitialPageObserver;
use App\Services\PaymentSummaryService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(PaymentSummaryService::class, function ($app) {
            return new PaymentSummaryService();
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        // التحقق من وجود متغير lang في الرابط
        if ($lang = $request->get('lang')) {
            if (in_array($lang, ['ar', 'en'])) {
                App::setLocale($lang);  // تعيين اللغة بناءً على الرابط
                session(['lang' => $lang]);  // تخزين اللغة في الجلسة
            }
        } else {
            // تعيين اللغة بناءً على الجلسة أو الافتراضي (العربية)
            App::setLocale(session('lang', 'ar'));
        }

        Schema::defaultStringLength(191);
    }
}
