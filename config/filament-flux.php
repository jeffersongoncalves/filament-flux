<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Scope class
    |--------------------------------------------------------------------------
    |
    | When set, the plugin wraps every Filament page with a <div> carrying this
    | class. The bundled CSS bridge maps Filament design tokens to Flux tokens
    | inside this scope. Set to null to disable the wrapper entirely.
    |
    */

    'scope_class' => 'filament-flux-scope',

    /*
    |--------------------------------------------------------------------------
    | Inject @fluxAppearance
    |--------------------------------------------------------------------------
    |
    | Renders @fluxAppearance inside <head>. Required for Flux dark mode and
    | runtime theme variables. Disable only if your layout already injects it.
    |
    */

    'inject_appearance' => true,

    /*
    |--------------------------------------------------------------------------
    | Inject @fluxScripts
    |--------------------------------------------------------------------------
    |
    | Renders @fluxScripts before </body>. Required for Flux interactive
    | components. Disable only if your layout already injects it.
    |
    */

    'inject_scripts' => true,

    /*
    |--------------------------------------------------------------------------
    | Live debounce (ms)
    |--------------------------------------------------------------------------
    |
    | Default debounce applied to wire:model.live.debounce on Flux fields when
    | live() is enabled.
    |
    */

    'live_debounce' => 500,

];
