@php
    $defaultTheme = filament()->getDefaultThemeMode()->value;
    $lightLabel = __('filament-panels::layout.actions.theme_switcher.light.label');
    $darkLabel = __('filament-panels::layout.actions.theme_switcher.dark.label');
    $systemLabel = __('filament-panels::layout.actions.theme_switcher.system.label');
@endphp

<div
    x-data="{
        theme: localStorage.getItem('theme') || @js($defaultTheme),
        setTheme(value) {
            this.theme = value;
            this.$dispatch('theme-changed', value);
        },
    }"
    class="fi-theme-switcher"
>
    <x-flux::dropdown align="end">
        <x-flux::button
            icon="sun"
            variant="ghost"
            size="sm"
            x-show="theme === 'light'"
            :aria-label="$lightLabel"
        />
        <x-flux::button
            icon="moon"
            variant="ghost"
            size="sm"
            x-show="theme === 'dark'"
            :aria-label="$darkLabel"
        />
        <x-flux::button
            icon="computer-desktop"
            variant="ghost"
            size="sm"
            x-show="theme === 'system'"
            :aria-label="$systemLabel"
        />

        <x-flux::menu>
            <x-flux::menu.item icon="sun" x-on:click="setTheme('light')">
                {{ $lightLabel }}
            </x-flux::menu.item>

            <x-flux::menu.item icon="moon" x-on:click="setTheme('dark')">
                {{ $darkLabel }}
            </x-flux::menu.item>

            <x-flux::menu.item icon="computer-desktop" x-on:click="setTheme('system')">
                {{ $systemLabel }}
            </x-flux::menu.item>
        </x-flux::menu>
    </x-flux::dropdown>
</div>
