<?php

namespace App\Http\Controllers;

use App\Services\TranslationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Inertia\Inertia;

class TranslationController extends Controller
{
    protected TranslationService $service;

    public function __construct(TranslationService $service)
    {
        $this->service = $service;
    }

    /**
     * Translations management page
     */
    public function index(Request $request)
    {
        $locale = $request->get('locale', App::getLocale());
        $group = $request->get('group');

        $translations = $this->service->getTranslations($locale);
        $supportedLocales = $this->service->getSupportedLocales();

        // Get groups
        $groups = array_keys($translations);

        return Inertia::render('Translations/Index', [
            'currentLocale' => $locale,
            'locales' => $supportedLocales,
            'translations' => $group ? ($translations[$group] ?? []) : $translations,
            'groups' => $groups,
            'activeGroup' => $group,
            'missing' => $locale !== 'en' ? $this->service->getMissingTranslations($locale) : [],
        ]);
    }

    /**
     * Switch locale
     */
    public function switchLocale(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|max:5',
        ]);

        $this->service->setLocale($request->locale);

        return back()->withCookie(
            cookie('locale', $request->locale, 60 * 24 * 365)
        );
    }

    /**
     * Save translation
     */
    public function store(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|max:5',
            'group' => 'required|string|max:50',
            'key' => 'required|string|max:255',
            'value' => 'required|string',
        ]);

        $this->service->saveTranslation(
            $request->locale,
            $request->group,
            $request->key,
            $request->value
        );

        return back()->with('success', 'Translation saved.');
    }

    /**
     * Update translation
     */
    public function update(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|max:5',
            'group' => 'required|string|max:50',
            'key' => 'required|string|max:255',
            'value' => 'nullable|string',
        ]);

        $this->service->saveTranslation(
            $request->locale,
            $request->group,
            $request->key,
            $request->value ?? ''
        );

        return back()->with('success', 'Translation updated.');
    }

    /**
     * Delete translation
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|max:5',
            'group' => 'required|string|max:50',
            'key' => 'required|string|max:255',
        ]);

        $this->service->deleteTranslation(
            $request->locale,
            $request->group,
            $request->key
        );

        return back()->with('success', 'Translation deleted.');
    }

    /**
     * Export translations as JSON
     */
    public function export(Request $request)
    {
        $locale = $request->get('locale', App::getLocale());
        $json = $this->service->exportToJson($locale);

        return response($json, 200, [
            'Content-Type' => 'application/json',
            'Content-Disposition' => "attachment; filename=translations_{$locale}.json",
        ]);
    }

    /**
     * Import translations from JSON
     */
    public function import(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|max:5',
            'file' => 'required|file|mimes:json',
        ]);

        $content = file_get_contents($request->file('file')->path());
        $count = $this->service->importFromJson($request->locale, $content);

        return back()->with('success', "{$count} translations imported.");
    }

    /**
     * Create new locale
     */
    public function createLocale(Request $request)
    {
        $request->validate([
            'locale' => 'required|string|max:5',
            'copy_from' => 'nullable|string|max:5',
        ]);

        $this->service->createLocale(
            $request->locale,
            $request->copy_from ?? 'en'
        );

        return back()->with('success', 'Locale created.');
    }

    /**
     * Get translations for frontend (JSON API)
     */
    public function getTranslations(Request $request)
    {
        $locale = $request->get('locale', App::getLocale());
        $translations = $this->service->getTranslations($locale);

        return response()->json([
            'locale' => $locale,
            'rtl' => $this->service->isRtl($locale),
            'translations' => $translations,
        ]);
    }
}
