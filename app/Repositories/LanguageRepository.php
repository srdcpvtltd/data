<?php

namespace App\Repositories;

use App\Models\Language;
use App\Models\LanguagePhrase;

abstract class LanguageRepository
{
    /**
     * @var Language
     */
    protected $language;

    /**
     * @var LanguagePhrase
     */
    protected $languagePhrase;

    /**
     * LanguagePhraseRepository constructor.
     * @param Language $language
     * @param LanguagePhrase $languagePhrase
     * @author Zohaib Hassan
     */
    public function __construct(Language $language, LanguagePhrase $languagePhrase)
    {
        $this->language = $language;
        $this->languagePhrase = $languagePhrase;
    }

    public function getTranslations($lang_id, $group)
    {
        return $this->languagePhrase->where('group', $group)
            ->where('lang_id', $lang_id)
            ->pluck('value', 'key')->all();
    }

    /**
     * @param $group
     * @param $phrases
     */
    public function updateTranslations($group, $phrases)
    {
        foreach ($phrases as $key => $value) {
            $this->language->phrases()->updateOrCreate([
                'group' => $group,
                'key' => $key,
            ], [
                'value' => $value
            ]);
        }
    }

    /**
     * @param array $data
     * @return
     * @throws \Exception
     */
    public function create(array $data)
    {
        try {
            $lang = $this->language->create([
                'name' => $data['name'],
                'locale' => $data['locale'],
                'direction' => $data['direction'],
            ]);

            $this->replicatePhrases($lang->id);

            return $lang;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }


    private function replicatePhrases($lang_id)
    {
        $phrases = $this->getPhrasesByLang(1);

        foreach ($phrases as $phrase)
        {
            $phraseReplica = $phrase->replicate();

            $phraseReplica->id = null;
            $phraseReplica->lang_id = $lang_id;

            $phraseReplica->save();
        }
    }

    /**
     * @param $id
     * @throws \Exception
     */
    public function delete($id)
    {
        $lang = $this->find($id);

        $this->deleteAllPhrasesByLang($lang->id);

        $lang->delete();
    }

    /**
     * @param array $data
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function update(array $data, $id)
    {
        try {
            $lang = $this->find($id);
            if (isset($data['default'])) {
                $this->language::query()->update(['default' => 0]);
            }
            return $lang->update($data);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param int $id
     * @return mixed|void
     * @throws \Exception
     */
    public function find($id = 0)
    {
        try {
            return $this->language->findOrFail($id);
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param int $id
     * @return mixed|void
     * @throws \Exception
     */
    public function findByLocale($locale = 'en')
    {
        try {
            return $this->language->where('locale', $locale)->first();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @return Language[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getAllLanguages()
    {
        try {
            return $this->language::all();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @return Language[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getAllActiveLanguages()
    {
        try {
            return $this->language::where('enabled', 1)->get();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @return Language[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getDefaultLanguage()
    {
        try {
            return $this->language::whereDefault(1)->first();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @return Language[]|\Illuminate\Database\Eloquent\Collection
     * @throws \Exception
     */
    public function getLanguageByLocale($locale)
    {
        try {
            return $this->language::whereLocale($locale)->first();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    /**
     * @param $lang_id
     * @return mixed
     */
    public function getPhrasesByLang($lang_id)
    {
        return $this->languagePhrase::where('lang_id', $lang_id)->get();
    }

    /**
     * @param $lang_id
     * @return mixed
     */
    public function deleteAllPhrasesByLang($lang_id)
    {
        return $this->languagePhrase->where('lang_id', $lang_id)->delete();
    }

    /**
     * @param string $group
     * @param array $keys
     * @return int
     */
    public function deletePhrasesByGroupKey(string $group, array $keys)
    {
        return $this->language
            ->phrases()
            ->where('group', $group)
            ->whereIn('key', $keys)
            ->delete();
    }

    public function getAllPhrasesGroups($lang_id)
    {
        return $this->languagePhrase->where('lang_id', $lang_id)
            ->groupBy('group')
            ->pluck('group')
            ->all();
    }
}
