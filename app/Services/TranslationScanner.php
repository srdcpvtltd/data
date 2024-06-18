<?php

namespace App\Services;

use App\Models\LanguagePhrase;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;

class TranslationScanner
{
    private Filesystem $disk;

    private array $scanPaths;

    private array $translationMethods;

    private array $excludePaths;

    public function __construct(Filesystem $disk, $config)
    {
        $this->disk = $disk;
        $this->scanPaths = $config['scan_paths'];
        $this->excludePaths = $config['exclude_paths'];
        $this->translationMethods = $config['translation_methods'];
    }

    /**
     * Scan all the files in the provided $scanPath for translations.
     *
     * @return array
     */
    public function findTranslations(): array
    {
        $langDirResults = [];
        foreach ($this->disk->allFiles(lang_path('en')) as $file) {
            $langArray = Arr::dot(include $file->getRealPath());
            $groupKey = $file->getFilenameWithoutExtension();
            foreach ($langArray as $key => $value) {
                $langDirResults[$groupKey][$key] = $value;
            }
        }

        // This has been derived from a combination of the following:
        // * Laravel Language Manager GUI from Mohamed Said (https://github.com/themsaid/laravel-langman-gui)
        // * Laravel 5 Translation Manager from Barry vd. Heuvel (https://github.com/barryvdh/laravel-translation-manager)
        $matchingPattern =
            '[^\w]' . // Must not start with any alphanum or _
            '(?<!->)' . // Must not start with ->
            '(' . implode('|', $this->translationMethods) . ')' . // Must start with one of the functions
            "\(" . // Match opening parentheses
            "[\'\"]" . // Match " or '
            '(' . // Start a new group to match:
            '.+' . // Must start with group
            ')' . // Close group
            "[\'\"]" . // Closing quote
            "[\),]";  // Close parentheses or new parameter

        $results = [];

        foreach ($this->disk->allFiles($this->scanPaths) as $file) {
            $path = array_filter($this->excludePaths, function ($path) use ($file) {
                return !str_contains($file->getPath(), $path);
            });

            if (empty($path)) {
                continue;
            }

            if (preg_match_all("/$matchingPattern/siU", $file->getContents(), $matches)) {
                foreach ($matches[2] as $key) {
                    $keyData = explode('.', $key);
                    $group = $keyData[0];

                    unset($keyData[0]);
                    $langKey = $value = implode('.', $keyData);

                    if (str_contains($langKey, '|')) {
                        $value = explode('|', $langKey)[1];
                    }
                    $results[strtolower($group)][$langKey] = $value;
                }
            }
        }

        return $results + $langDirResults;
    }

    public function getMissingTranslationsFromDB(): array
    {
        $dbData = $this->getTranslationsFromDB();
        $keysFromFiles = $this->findTranslations();
        $missingData = [];

        foreach ($keysFromFiles as $group => $phrases) {
            foreach ($phrases as $key => $phrase) {
                if (!isset($dbData[$group][$key])) {
                    $missingData[$group][$key] = $phrase;
                }
            }
        }

        return $missingData;
    }

    public function getMissingTranslationsToDelete(): array
    {
        $missingData = [];

        $dbData = $this->getTranslationsFromDB();
        $keysFromFiles = $this->findTranslations();

        foreach ($dbData as $group => $phrases) {
            foreach ($phrases as $key => $phrase) {
                if (!isset($keysFromFiles[$group][$key])) {
                    $missingData[$group][$key] = $phrase;
                }
            }
        }

        return $missingData;
    }

    public function getTranslationsFromDB(): array
    {
        $dbData = [];

        $keysFromDB = LanguagePhrase::select(['group', 'key', 'value'])
            ->where('lang_id', 1)
            ->get()
            ->groupBy('group')->toArray();

        foreach ($keysFromDB as $group => $phrases) {
            foreach ($phrases as $phrase) {
                $dbData[$group][$phrase['key']] = $phrase['value'];
            }
        }

        return $dbData;
    }
}
