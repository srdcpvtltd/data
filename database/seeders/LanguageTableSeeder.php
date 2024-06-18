<?php

namespace Database\Seeders;

use App\Models\Language;
use App\Models\LanguagePhrase;
use App\Services\LanguageService;
use App\Services\TranslationScanner;
use Illuminate\Database\Seeder;

class LanguageTableSeeder extends Seeder
{
    public mixed $scanner;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        try {
            Language::insert([
                [
                    'locale' => 'en',
                    'name' => 'English',
                    'default' => 1,
                    'enabled' => 1,
                ]
            ]);

            $this->scanner = app()->make(TranslationScanner::class);
            $matches = $this->scanner->findTranslations();

            foreach ($matches as $group => $phrases) {
                $service = new LanguageService(Language::findByLocale('en'), new LanguagePhrase());
                $service->updateTranslations($group, $phrases);
            }
        } catch (\PDOException $exception) {
        }
    }
}
