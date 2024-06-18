<?php

namespace App\Providers;

use App\Loader\DBTranslationsLoader;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\TranslationServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Schema;
use App\Services\TranslationScanner;

class TranslationServiceProvider extends ServiceProvider
{
    public function register()
    {
        parent::register();

        $this->app->singleton(TranslationScanner::class, function () {
            $config = $this->app['config']['translation'];

            return new TranslationScanner(new Filesystem, $config);
        });
    }
    /**
     * Register the translation line loader.
     *
     * @return void
     */
    protected function registerLoader()
    {
        $this->app->singleton('translation.loader', function ($app) {
            try {
                if (Schema::hasTable('languages') && request()->segment(1) != 'install') {
                    return new DBTranslationsLoader();
                }
            } catch(\PDOException $e) {}

            return new FileLoader($app['files'], $app['path.lang']);

        });
    }
}
