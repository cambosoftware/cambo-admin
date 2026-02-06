<?php

namespace CamboSoftware\CamboAdmin\Tests\Unit\Models;

use CamboSoftware\CamboAdmin\Models\Setting;
use CamboSoftware\CamboAdmin\Tests\TestCase;

class SettingTest extends TestCase
{
    public function test_setting_can_be_created(): void
    {
        $setting = Setting::create([
            'group' => 'general',
            'key' => 'app_name',
            'label' => 'Application Name',
            'value' => 'My App',
            'type' => 'text',
        ]);

        $this->assertDatabaseHas('settings', [
            'key' => 'app_name',
            'value' => 'My App',
        ]);
    }

    public function test_get_setting_value(): void
    {
        Setting::create([
            'group' => 'general',
            'key' => 'site_title',
            'label' => 'Site Title',
            'value' => 'My Website',
            'type' => 'text',
        ]);

        $this->assertEquals('My Website', Setting::get('site_title'));
    }

    public function test_get_setting_with_default(): void
    {
        $value = Setting::get('nonexistent_key', 'default_value');

        $this->assertEquals('default_value', $value);
    }

    public function test_set_setting_value(): void
    {
        Setting::create([
            'group' => 'general',
            'key' => 'timezone',
            'label' => 'Timezone',
            'value' => 'UTC',
            'type' => 'text',
        ]);

        Setting::set('timezone', 'Europe/Paris');

        $this->assertEquals('Europe/Paris', Setting::get('timezone'));
    }

    public function test_get_boolean_setting(): void
    {
        Setting::create([
            'group' => 'features',
            'key' => 'maintenance_mode',
            'label' => 'Maintenance Mode',
            'value' => '1',
            'type' => 'boolean',
        ]);

        // Setting::get() returns the typed_value which auto-converts boolean
        $this->assertTrue(Setting::get('maintenance_mode'));
    }

    public function test_get_integer_setting(): void
    {
        Setting::create([
            'group' => 'limits',
            'key' => 'max_upload_size',
            'label' => 'Max Upload Size',
            'value' => '10240',
            'type' => 'integer',
        ]);

        // Setting::get() returns the typed_value which auto-converts to integer
        $this->assertEquals(10240, Setting::get('max_upload_size'));
        $this->assertIsInt(Setting::get('max_upload_size'));
    }

    public function test_get_settings_by_group(): void
    {
        Setting::create(['group' => 'email', 'key' => 'smtp_host', 'label' => 'SMTP Host', 'value' => 'mail.example.com', 'type' => 'text']);
        Setting::create(['group' => 'email', 'key' => 'smtp_port', 'label' => 'SMTP Port', 'value' => '587', 'type' => 'number']);
        Setting::create(['group' => 'general', 'key' => 'app_name', 'label' => 'App Name', 'value' => 'Test', 'type' => 'text']);

        $emailSettings = Setting::forGroup('email');

        $this->assertCount(2, $emailSettings);
        $this->assertTrue($emailSettings->pluck('key')->contains('smtp_host'));
        $this->assertTrue($emailSettings->pluck('key')->contains('smtp_port'));
    }

    public function test_groups_returns_predefined_groups(): void
    {
        $groups = Setting::groups();

        $this->assertIsArray($groups);
        $this->assertArrayHasKey('general', $groups);
        $this->assertArrayHasKey('appearance', $groups);
        $this->assertArrayHasKey('email', $groups);
        $this->assertArrayHasKey('security', $groups);
    }

    public function test_all_grouped_returns_settings_by_group(): void
    {
        Setting::create(['group' => 'general', 'key' => 'test1', 'label' => 'Test 1', 'value' => 'val1', 'type' => 'text']);
        Setting::create(['group' => 'email', 'key' => 'test2', 'label' => 'Test 2', 'value' => 'val2', 'type' => 'text']);

        $grouped = Setting::allGrouped();

        $this->assertIsArray($grouped);
        $this->assertArrayHasKey('general', $grouped);
        $this->assertArrayHasKey('email', $grouped);
    }

    public function test_get_public_settings(): void
    {
        Setting::create(['group' => 'general', 'key' => 'public_key', 'label' => 'Public', 'value' => 'public_value', 'type' => 'text', 'is_public' => true]);
        Setting::create(['group' => 'general', 'key' => 'private_key', 'label' => 'Private', 'value' => 'private_value', 'type' => 'text', 'is_public' => false]);

        $publicSettings = Setting::getPublic();

        $this->assertArrayHasKey('public_key', $publicSettings);
        $this->assertArrayNotHasKey('private_key', $publicSettings);
    }

    public function test_define_creates_or_updates_setting(): void
    {
        Setting::define([
            'group' => 'test',
            'key' => 'test_setting',
            'label' => 'Test Setting',
            'value' => 'initial',
            'type' => 'text',
        ]);

        $this->assertEquals('initial', Setting::get('test_setting'));

        Setting::define([
            'group' => 'test',
            'key' => 'test_setting',
            'label' => 'Test Setting Updated',
            'value' => 'updated',
            'type' => 'text',
        ]);

        $this->assertEquals('updated', Setting::get('test_setting'));
        // Should only have one record
        $this->assertEquals(1, Setting::where('key', 'test_setting')->count());
    }

    public function test_set_many_updates_multiple_settings(): void
    {
        Setting::create(['group' => 'general', 'key' => 'setting_a', 'label' => 'A', 'value' => 'a', 'type' => 'text']);
        Setting::create(['group' => 'general', 'key' => 'setting_b', 'label' => 'B', 'value' => 'b', 'type' => 'text']);

        Setting::setMany([
            'setting_a' => 'new_a',
            'setting_b' => 'new_b',
        ]);

        $this->assertEquals('new_a', Setting::get('setting_a'));
        $this->assertEquals('new_b', Setting::get('setting_b'));
    }
}
