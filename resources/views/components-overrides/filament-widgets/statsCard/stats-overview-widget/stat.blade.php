@php
    use Filament\Support\Enums\IconPosition;
    use Filament\Widgets\View\Components\StatsOverviewWidgetComponent\StatComponent\StatsOverviewWidgetStatChartComponent;
    use Illuminate\View\ComponentAttributeBag;

    $chartColor = $getChartColor() ?? 'gray';
    $descriptionColor = $getDescriptionColor();
    $descriptionIcon = $getDescriptionIcon();
    $descriptionIconPosition = $getDescriptionIconPosition();
    $url = $getUrl();
    $isLink = filled($url);
    $tag = $isLink ? 'a' : 'div';
    $chart = $getChart();
    $chartDataChecksum = $generateChartDataChecksum();
    $description = $getDescription();
    $descriptionFluxColor = is_string($descriptionColor) ? $descriptionColor : null;

    $outerBag = $getExtraAttributeBag()->class([
        'fi-wi-stats-overview-stat',
        'fi-stats-flux',
    ]);
@endphp

<{!! $tag !!}
    @if ($isLink)
        {{ \Filament\Support\generate_href_html($url, $shouldOpenUrlInNewTab()) }}
    @endif
    {{ $outerBag }}
>
    <flux:card class="fi-stats-flux-card">
        <div class="fi-wi-stats-overview-stat-content flex flex-col gap-2">
            <flux:subheading class="fi-wi-stats-overview-stat-label-ctn flex items-center gap-2">
                {{ \Filament\Support\generate_icon_html($getIcon()) }}

                <span class="fi-wi-stats-overview-stat-label">
                    {{ $getLabel() }}
                </span>
            </flux:subheading>

            <flux:heading size="xl" class="fi-wi-stats-overview-stat-value">
                {{ $getValue() }}
            </flux:heading>

            @if (filled($description))
                <flux:text
                    @if ($descriptionFluxColor) color="{{ $descriptionFluxColor }}" @endif
                    class="fi-wi-stats-overview-stat-description flex items-center gap-1"
                >
                    @if ($descriptionIcon && in_array($descriptionIconPosition, [IconPosition::Before, 'before']))
                        {{ \Filament\Support\generate_icon_html($descriptionIcon, attributes: (new ComponentAttributeBag)) }}
                    @endif

                    <span>{{ $description }}</span>

                    @if ($descriptionIcon && in_array($descriptionIconPosition, [IconPosition::After, 'after']))
                        {{ \Filament\Support\generate_icon_html($descriptionIcon, attributes: (new ComponentAttributeBag)) }}
                    @endif
                </flux:text>
            @endif
        </div>

        @if ($chart)
            <div x-data="{ statsOverviewStatChart() {} }">
                <div
                    x-load
                    x-load-src="{{ \Filament\Support\Facades\FilamentAsset::getAlpineComponentSrc('stats-overview/stat/chart', 'filament/widgets') }}"
                    x-data="statsOverviewStatChart({
                                dataChecksum: @js($chartDataChecksum),
                                labels: @js(array_keys($chart)),
                                values: @js(array_values($chart)),
                            })"
                    {{ (new ComponentAttributeBag)->color(StatsOverviewWidgetStatChartComponent::class, $chartColor)->class(['fi-wi-stats-overview-stat-chart']) }}
                >
                    <canvas x-ref="canvas"></canvas>

                    <span
                        x-ref="backgroundColorElement"
                        class="fi-wi-stats-overview-stat-chart-bg-color"
                    ></span>

                    <span
                        x-ref="borderColorElement"
                        class="fi-wi-stats-overview-stat-chart-border-color"
                    ></span>
                </div>
            </div>
        @endif
    </flux:card>
</{!! $tag !!}>
