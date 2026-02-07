<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Inertia\Inertia;

class SettingController extends Controller
{
    /**
     * Display settings page.
     */
    public function index(Request $request)
    {
        $activeGroup = $request->get('group', 'general');

        $settings = Setting::forGroup($activeGroup);
        $groups = Setting::groups();

        // Add count to each group
        foreach ($groups as $key => &$group) {
            $group['count'] = Setting::where('group', $key)->count();
        }

        return Inertia::render('Settings/Index', [
            'settings' => $settings,
            'groups' => $groups,
            'activeGroup' => $activeGroup,
        ]);
    }

    /**
     * Update settings.
     */
    public function update(Request $request)
    {
        $data = $request->validate([
            'settings' => 'required|array',
        ]);

        foreach ($data['settings'] as $key => $value) {
            $setting = Setting::where('key', $key)->first();

            if (!$setting) {
                continue;
            }

            // Validate the value if rules exist
            if ($setting->validation) {
                $validator = Validator::make(
                    [$key => $value],
                    [$key => $setting->validation]
                );

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }
            }

            $setting->value = $value;
            $setting->save();
        }

        return back()->with('success', 'Paramètres mis à jour.');
    }

    /**
     * Get a specific setting (API).
     */
    public function show(string $key)
    {
        $setting = Setting::where('key', $key)->first();

        if (!$setting) {
            abort(404);
        }

        // Check if setting is public or user is authenticated
        if (!$setting->is_public && !auth()->check()) {
            abort(403);
        }

        return response()->json([
            'key' => $setting->key,
            'value' => $setting->typed_value,
        ]);
    }

    /**
     * Get public settings (API).
     */
    public function publicSettings()
    {
        return response()->json(Setting::getPublic());
    }

    /**
     * Create a new setting.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'group' => 'required|string|max:50',
            'key' => 'required|string|max:100|unique:settings',
            'label' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:text,textarea,number,boolean,select,multiselect,json,color,file',
            'default_value' => 'nullable',
            'options' => 'nullable|array',
            'validation' => 'nullable|array',
            'is_public' => 'boolean',
            'is_encrypted' => 'boolean',
            'order' => 'integer',
        ]);

        $setting = Setting::create($data);

        return back()->with('success', 'Paramètre créé.');
    }

    /**
     * Delete a setting.
     */
    public function destroy(Setting $setting)
    {
        $setting->delete();

        return back()->with('success', 'Paramètre supprimé.');
    }

    /**
     * Reset settings to defaults for a group.
     */
    public function resetGroup(Request $request)
    {
        $request->validate([
            'group' => 'required|string',
        ]);

        Setting::where('group', $request->group)
            ->each(function ($setting) {
                $setting->value = $setting->default_value;
                $setting->save();
            });

        return back()->with('success', 'Paramètres réinitialisés.');
    }
}
