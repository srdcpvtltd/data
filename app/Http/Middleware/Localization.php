<?php

namespace App\Http\Middleware;

use App\Services\LanguageService;
use Closure;

class Localization
{

    protected $lang;

    public function __construct(LanguageService $languageService)
    {
        $this->lang = $languageService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @throws \Exception
     */
    public function handle($request, Closure $next)
    {
        if(\Session::has('locale'))
        {
            \App::setlocale(\Session::get('locale'));

            if (!session('lang_dir')) {
                try {
                    $dir = $this->lang->getLanguageByLocale(\Session::get('locale'))->direction;

                    session()->put('lang_dir', $dir);
                } catch (\Illuminate\Database\QueryException | \ErrorException $exception) { }
            }
        } else {
            $locale = 'en';

            try {
                $locale = $this->lang->getDefaultLanguage()->locale;
            } catch (\Illuminate\Database\QueryException | \ErrorException $exception) { }

            \App::setlocale($locale);
        }

        return $next($request);
    }
}
