<?php

namespace CamboSoftware\CamboAdmin\Tests\Unit\Services;

use CamboSoftware\CamboAdmin\Services\TranslationService;
use CamboSoftware\CamboAdmin\Tests\TestCase;

class TranslationServiceTest extends TestCase
{
    protected TranslationService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new TranslationService();
    }

    public function test_get_supported_locales_returns_array(): void
    {
        $locales = $this->service->getSupportedLocales();

        $this->assertIsArray($locales);
        $this->assertNotEmpty($locales);
    }

    public function test_get_current_locale_returns_string(): void
    {
        $locale = $this->service->getCurrentLocale();

        $this->assertIsString($locale);
        $this->assertNotEmpty($locale);
    }

    public function test_set_locale_changes_app_locale(): void
    {
        $this->service->setLocale('fr');

        $this->assertEquals('fr', app()->getLocale());
    }

    public function test_is_rtl_returns_boolean(): void
    {
        $this->assertIsBool($this->service->isRtl('en'));
        $this->assertIsBool($this->service->isRtl('ar'));
    }

    public function test_arabic_is_rtl(): void
    {
        // Check if Arabic returns true for RTL when in supported locales
        $locales = $this->service->getSupportedLocales();

        // Arabic should be in supported locales (set in TestCase config)
        $this->assertArrayHasKey('ar', $locales);
        $this->assertTrue($locales['ar']['rtl']);
    }

    public function test_english_is_not_rtl(): void
    {
        $this->assertFalse($this->service->isRtl('en'));
    }

    public function test_french_is_not_rtl(): void
    {
        $this->assertFalse($this->service->isRtl('fr'));
    }

    public function test_supported_locale_has_required_info(): void
    {
        $locales = $this->service->getSupportedLocales();

        foreach ($locales as $code => $info) {
            $this->assertArrayHasKey('name', $info, "Locale {$code} should have 'name'");
            $this->assertArrayHasKey('native', $info, "Locale {$code} should have 'native'");
            $this->assertArrayHasKey('rtl', $info, "Locale {$code} should have 'rtl'");
            $this->assertArrayHasKey('flag', $info, "Locale {$code} should have 'flag'");
        }
    }

    public function test_get_translations_returns_array(): void
    {
        $translations = $this->service->getTranslations('en');

        $this->assertIsArray($translations);
    }

    public function test_export_to_json_returns_valid_json(): void
    {
        $json = $this->service->exportToJson('en');

        $this->assertIsString($json);
        $this->assertJson($json);
    }

    public function test_get_missing_translations_returns_array(): void
    {
        $missing = $this->service->getMissingTranslations('fr', 'en');

        $this->assertIsArray($missing);
    }
}
