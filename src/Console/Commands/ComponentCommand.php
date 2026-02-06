<?php

namespace CamboSoftware\CamboAdmin\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Str;

class ComponentCommand extends Command
{
    protected $signature = 'cambo:component
                            {name : The name of the component}
                            {--category= : Category folder (UI, Form, Data, etc.)}
                            {--with-props : Include example props}
                            {--with-emits : Include example emits}
                            {--with-slots : Include example slots}
                            {--force : Overwrite existing file}';

    protected $description = 'Generate a Vue component';

    protected Filesystem $files;

    public function __construct(Filesystem $files)
    {
        parent::__construct();
        $this->files = $files;
    }

    public function handle(): int
    {
        $componentName = Str::studly($this->argument('name'));
        $category = $this->option('category');

        $path = $category
            ? resource_path("js/Components/{$category}/{$componentName}.vue")
            : resource_path("js/Components/{$componentName}.vue");

        $dir = dirname($path);

        if (!$this->files->isDirectory($dir)) {
            $this->files->makeDirectory($dir, 0755, true);
        }

        if (!$this->option('force') && $this->files->exists($path)) {
            $this->error("Component already exists: {$componentName}.vue");
            return self::FAILURE;
        }

        $content = $this->generateContent($componentName);

        $this->files->put($path, $content);

        $displayPath = $category ? "{$category}/{$componentName}.vue" : "{$componentName}.vue";
        $this->info("✓ Component created: {$displayPath}");

        return self::SUCCESS;
    }

    protected function generateContent(string $name): string
    {
        $propsCode = '';
        $emitsCode = '';
        $slotsCode = '';

        if ($this->option('with-props')) {
            $propsCode = <<<JS

const props = defineProps({
    /**
     * The variant style of the component
     */
    variant: {
        type: String,
        default: 'default',
        validator: (value) => ['default', 'primary', 'secondary'].includes(value),
    },
    /**
     * Whether the component is disabled
     */
    disabled: {
        type: Boolean,
        default: false,
    },
})
JS;
        }

        if ($this->option('with-emits')) {
            $emitsCode = <<<JS

const emit = defineEmits(['click', 'change', 'update:modelValue'])

const handleClick = (event) => {
    if (!props.disabled) {
        emit('click', event)
    }
}
JS;
        }

        if ($this->option('with-slots')) {
            $slotsCode = <<<HTML

        <!-- Default slot -->
        <slot />

        <!-- Named slot example -->
        <template v-if="\$slots.header">
            <div class="header">
                <slot name="header" />
            </div>
        </template>

        <!-- Scoped slot example -->
        <slot name="item" :data="{ example: 'data' }" />
HTML;
        } else {
            $slotsCode = <<<HTML

        <slot />
HTML;
        }

        return <<<VUE
<script setup>
/**
 * {$name} Component
 *
 * @description A reusable component
 */
{$propsCode}{$emitsCode}
</script>

<template>
    <div class="{$this->getDefaultClasses()}">
{$slotsCode}
    </div>
</template>
VUE;
    }

    protected function getDefaultClasses(): string
    {
        return 'relative';
    }
}
