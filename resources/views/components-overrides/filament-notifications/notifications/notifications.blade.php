@php
    use Filament\Support\Enums\Alignment;
    use Filament\Support\Enums\VerticalAlignment;

    $alignment = static::$alignment;
    $verticalAlignment = static::$verticalAlignment;

    $alignmentValue = $alignment instanceof Alignment ? $alignment->value : (is_string($alignment) ? $alignment : 'end');
    $verticalAlignmentValue = $verticalAlignment instanceof VerticalAlignment
        ? $verticalAlignment->value
        : (is_string($verticalAlignment) ? $verticalAlignment : 'end');

    // Flux's `<flux:toast.group position="...">` accepts a "vertical
    // horizontal" pair where `vertical ∈ {top, bottom}` and
    // `horizontal ∈ {start, center, end}`. Filament's vertical alignment
    // uses {start, center, end}; map start → top, end → bottom, center
    // falls back to bottom because Flux has no vertical center.
    $vertical = match ($verticalAlignmentValue) {
        'start', 'top' => 'top',
        default => 'bottom',
    };

    $horizontal = match ($alignmentValue) {
        'start', 'left' => 'start',
        'center' => 'center',
        default => 'end',
    };

    $fluxPosition = "{$vertical} {$horizontal}";
@endphp

<div>
    <x-flux::toast.group
        :position="$fluxPosition"
        @class([
            'fi-no',
            'fi-align-'.$alignmentValue,
            'fi-vertical-align-'.$verticalAlignmentValue,
        ])
        role="status"
    >
        @foreach ($notifications as $notification)
            {{ $notification }}
        @endforeach
    </x-flux::toast.group>

    @if ($broadcastChannel = $this->getBroadcastChannel())
        @script
            <script>
                // Bind once per component instance. This block can be evaluated again for
                // the same component, and every extra call stacks another subscription on
                // the Echo channel, so a single broadcast notification would fire
                // handleBroadcastNotification once per stacked subscription.
                window.__fluxNotificationsBound = window.__fluxNotificationsBound || new Set()

                if (!window.__fluxNotificationsBound.has($wire.id)) {
                    window.__fluxNotificationsBound.add($wire.id)

                    window.addEventListener('EchoLoaded', () => {
                        window.Echo.private(@js($broadcastChannel)).notification(
                            (notification) => {
                                setTimeout(
                                    () =>
                                        $wire.handleBroadcastNotification(
                                            notification,
                                        ),
                                    500,
                                )
                            },
                        )
                    })

                    if (window.Echo) {
                        window.dispatchEvent(new CustomEvent('EchoLoaded'))
                    }
                }
            </script>
        @endscript
    @endif
</div>
