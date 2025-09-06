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
use Illuminate\Support\ServiceProvider;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
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
    }
}
