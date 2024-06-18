<?php

namespace App\Providers;

use App\Models\Language;
use App\Models\LanguagePhrase;
use App\Models\Term;
use App\Repositories\CategoriesRepository;
use App\Services\CategoriesService;
use App\Services\LanguageService;
use DB;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use PDOException;
use View;
use function request;

class AppServiceProvider extends ServiceProvider
{


    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        if ($this->app->isLocal() && class_exists(\Laravel\Telescope\TelescopeServiceProvider::class)) {
            $this->app->register(TelescopeServiceProvider::class);
        }
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Paginator::useBootstrap();
        Schema::defaultStringLength(191);

        try {
            $settings = setting();

            config()->set($settings);

            $current_url = get_base_url();

            config()->set('app.url', $current_url);

            config()->set('filesystems.disks.public.url', $current_url . '/storage');

            if (setting('taxes.enabled', 'no') == 'no') {
                config()->set('cart.tax', 0);
            }


            if (setting('app.name') != '') {
                config()->set('mail.from.name', setting('app.name'));
            }

            $assetUrl = explode(':', config('app.url'));

            if (isset($assetUrl[1])) {
                config()->set('app.asset_url', rtrim($assetUrl[1], '/'));
            }

            if (\setting('app.url')) {
                config()->set('services.facebook.redirect', rtrim(\setting('app.url'), '/').'/auth/facebook/callback');
                config()->set('services.envato.redirect', rtrim(\setting('app.url'), '/').'/auth/envato/callback');
            }
        } catch (QueryException $ex) {
            if (!app()->runningInConsole()) {
                if (request()->segment(1) != 'installer' && request()->segment(1) != 'install-complete') {
                    header('Location: ' . url('installer'));
                    exit;
                }
            }
        }

        try {
            if (Schema::hasTable('languages')) {
                View::composer('*', function ($view) {
                    $language = new LanguageService(new Language(), new LanguagePhrase());
                    $active_languages = $language->getAllActiveLanguages();

                    if (session()->has('locale')) {
                        $default_lang = $language->getLanguageByLocale(session()->get('locale'));
                    } else {
                        $default_lang = $language->getDefaultLanguage();
                    }

                    $view->with(compact('active_languages', 'default_lang'));
                });
            }

            View::composer('themes.default.app', function ($view) {
                $view->with('categories', (new CategoriesService(new CategoriesRepository(new Term())))->menu());
            });
        } catch (PDOException $e) {
        }

        try {
            if (\setting('app.url') != null) {
                if (!app()->runningInConsole()) {

                    $requested_url = explode(':', rtrim(url('/'), '/'));
                    $setting_url = explode(':', rtrim(\setting('app.url'), '/'));
                    $protocol = ((isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] != 'off') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO'])) && ($_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https')) ? 'https' : 'http';

                    if ($protocol != $setting_url[0] ||  $requested_url[1] != $setting_url[1]) {

                        if (request()->path() == '/') {
                            $request_path = '';
                        } else {
                            $request_path = '/' . request()->path();
                        }

                        header("Location: " . rtrim(\setting('app.url'), '/') . $request_path);
                        exit;
                    }
                }
            }
        } catch (QueryException $ex) {}

    }
}
