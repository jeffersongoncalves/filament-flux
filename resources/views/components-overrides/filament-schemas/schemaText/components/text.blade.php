@php
    use Filament\Support\Enums\FontFamily;
    use Filament\Support\Enums\FontWeight;
    use Filament\Support\Enums\TextSize;

    $color = $getColor();
    $content = $getContent();
    $icon = $getIcon();
    $iconPosition = $getIconPosition();
    $iconSize = $getIconSize();
    $size = $getSize();
    $tooltip = $getTooltip();
    $weight = $getWeight();
    $fontFamily = $getFontFamily();

    $copyableState = $getCopyableState($content) ?? $content;
    $copyMessage = $getCopyMessage($copyableState);
    $copyMessageDuration = $getCopyMessageDuration($copyableState);
    $isCopyable = $isCopyable($copyableState);
@endphp

@if ($isBadge())
    {{-- Delegate badge variant to Filament — already overridable via the
         `badge` component slug. --}}
    <x-filament::badge
        :color="$color"
        :icon="$icon"
        :icon-position="$iconPosition"
        :icon-size="$iconSize"
        :size="$size instanceof TextSize ? $size->value : $size"
        :tag="$isCopyable ? 'button' : 'span'"
        :tooltip="$tooltip"
        :attributes="\Filament\Support\prepare_inherited_attributes($getExtraAttributeBag()->class(['fi-sc-text']))"
    >
        {{ $content }}
    </x-filament::badge>
@else
    @php
        $fluxSize = match (true) {
            $size === TextSize::ExtraSmall, $size === 'xs' => 'xs',
            $size === TextSize::Small, $size === 'sm' => 'sm',
            $size === TextSize::Large, $size === 'lg' => 'lg',
            default => null,
        };

        $fluxPalette = ['red', 'orange', 'amber', 'yellow', 'lime', 'green', 'emerald', 'teal', 'cyan', 'sky', 'blue', 'indigo', 'violet', 'purple', 'fuchsia', 'pink', 'rose'];

        $fluxColor = match (true) {
            ! is_string($color) => null,
            in_array($color, $fluxPalette, true) => $color,
            $color === 'primary' => 'blue',
            $color === 'success' => 'lime',
            $color === 'warning' => 'amber',
            $color === 'danger' => 'red',
            $color === 'info' => 'cyan',
            default => null,
        };

        $fluxVariant = (! $fluxColor && in_array($color, ['gray', 'zinc', 'slate', 'neutral', 'stone', 'secondary'], true)) ? 'subtle' : null;

        $bag = (new \Illuminate\View\ComponentAttributeBag(array_filter([
            'size' => $fluxSize,
            'color' => $fluxColor,
            'variant' => $fluxVariant,
        ], fn ($v) => $v !== null && $v !== '')))
            ->merge($getExtraAttributes(), escape: false)
            ->class([
                'fi-sc-text',
                'fi-copyable' => $isCopyable,
                ($weight instanceof FontWeight) ? "fi-font-{$weight->value}" : (is_string($weight) ? $weight : null),
                ($fontFamily instanceof FontFamily) ? "fi-font-{$fontFamily->value}" : (is_string($fontFamily) ? $fontFamily : null),
            ]);

        if ($isCopyable) {
            $bag = $bag->merge([
                'x-on:click' => '
                    window.navigator.clipboard.writeText('.\Illuminate\Support\Js::from($copyableState).')
                    $tooltip('.\Illuminate\Support\Js::from($copyMessage).', {
                        theme: $store.theme,
                        timeout: '.\Illuminate\Support\Js::from($copyMessageDuration).',
                    })
                ',
            ], escape: false);
        }

        if (filled($tooltip)) {
            $bag = $bag->merge([
                'x-tooltip' => '{
                    content: '.\Illuminate\Support\Js::from($tooltip).',
                    theme: $store.theme,
                    allowHTML: '.($tooltip instanceof \Illuminate\Contracts\Support\Htmlable ? 'true' : 'false').',
                }',
            ], escape: false);
        }
    @endphp

    <x-flux::text :attributes="$bag">{{ $content }}</x-flux::text>
@endif
