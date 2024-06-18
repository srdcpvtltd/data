<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Language;
use App\Models\LanguagePhrase;
use App\Services\LanguageService;
use App\Services\TranslationScanner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laracasts\Flash\Flash;

class AdminLanguageController extends Controller
{

    protected $data = [];
    protected $langService = [];

    public function __construct(LanguageService $languageService)
    {
        $this->langService = $languageService;

        $this->data['title'] = 'Languages';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function index()
    {
        $this->data['languages'] = $this->langService->getAllLanguages();

        return view('admin.language.index', $this->data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $this->data['title'] = 'Add new Language';

        return view('admin.language.create', $this->data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return void
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'locale' => 'required|unique:languages,locale'
        ]);

        $lang = $this->langService->create($request->all());

        Flash::success('Language created successfully.');

        return redirect()->route('ch-admin.language.edit', [$lang->id]);
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }


    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function phrases($id, $active_group = 'general')
    {
        $this->data['language'] = $this->langService->find($id);
        $this->data['title'] = 'Edit Phrases for ' . $this->data['language']->name;
        $this->data['active_group'] = $active_group;

        $phrases = $this->langService->getPhrasesByLang($id);

        $this->data['phrases'] = $phrases->where('group', $active_group);

        $groups = $phrases->groupBy('group')->keys()->mapWithKeys(function ($row) {
            if ($row == '*') {
                return [$row => 'General'];
            }

            return [$row => ucfirst(str_replace('_', ' ', $row))];
        })->toArray();

        unset($groups['general']);
        $this->data['groups'] = ['general' => 'General'] + $groups;

        return view('admin.language.phrases', $this->data);
    }


    public function phrasesUpdate(Request $request, $lang_id, $group)
    {
        try {
            $languageService = new LanguageService(Language::find($lang_id), new LanguagePhrase());
            $languageService->updateTranslations($group, $request->input('phrases'));
        } catch (\Exception $exception) {
            Flash::error('Error: ' . $exception->getMessage());

            return redirect()->back();
        }

        Flash::success('Phrases updated successfully.');

        return redirect()->back();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function edit($id)
    {
        $this->data['language'] = $this->langService->find($id);

        return view('admin.language.edit', $this->data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     * @throws \Exception
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required_without_all:enabled,default',
            'locale' => 'required_without_all:enabled,default|unique:languages,locale,' . $id,
        ]);

        $this->langService->update($request->all(), $id);;

        Flash::success('Language updated successfully.');

        if ($request->has('enabled') || $request->has('default')) {
            return redirect()->route('ch-admin.language.index');
        } else {
            return redirect()->route('ch-admin.language.edit', [$id]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return void
     * @throws \Exception
     */
    public function destroy($id)
    {
        $this->langService->delete($id);

        Flash::success('Language deleted successfully.');

        return redirect()->route('ch-admin.language.index');
    }

    public function sync(): RedirectResponse
    {
        $scanner = app()->make(TranslationScanner::class);

        $missingData = $scanner->getMissingTranslationsFromDB();
        $allMatches = $scanner->findTranslations();

        $languages = Language::all();

        foreach ($languages as $language) {
            $languageService = new LanguageService($language, new LanguagePhrase());
            if (count($missingData)) {
                foreach ($missingData as $group => $phrases) {
                    $languageService->updateTranslations($group, $phrases);
                }
            }

            $keysToDelete = $scanner->getMissingTranslationsToDelete();
            foreach ($keysToDelete as $group => $phrases) {
                $languageService->deletePhrasesByGroupKey($group, array_keys($phrases));
            }

            foreach ($allMatches as $group => $phrases) {
                $languageService->updateTranslations($group, $phrases);
            }
        }

        Flash::success('Phrases Synced and refreshed successfully.');

        return redirect()->route('ch-admin.language.index');
    }
}
