<div>
    @php
        $navigation = filament()->getNavigation();
        $isRtl = __('filament-panels::layout.direction') === 'rtl';
        $isSidebarCollapsibleOnDesktop = filament()->isSidebarCollapsibleOnDesktop();
        $isSidebarFullyCollapsibleOnDesktop = filament()->isSidebarFullyCollapsibleOnDesktop();
        $hasNavigation = filament()->hasNavigation();
        $hasTopbar = filament()->hasTopbar();
        // Map both Filament collapsibility intents to Flux's compact mode
        // (`collapsible="true"`). Flux's `mobile` value never collapses on
        // desktop, so a panel configured with `fullyCollapsibleOnDesktop()`
        // would lose the desktop toggle entirely. `true` enables both.
        $sidebarCollapsible = ($isSidebarCollapsibleOnDesktop || $isSidebarFullyCollapsibleOnDesktop) ? true : null;
    @endphp

    <x-flux::sidebar
        :collapsible="$sidebarCollapsible"
        sticky
        x-bind:class="{ 'fi-sidebar-open': $store.sidebar.isOpen }"
        class="fi-sidebar fi-main-sidebar"
    >
        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_START) }}

        <x-flux::sidebar.header>
            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_LOGO_BEFORE) }}

            @if ($homeUrl = filament()->getHomeUrl())
                <a {{ \Filament\Support\generate_href_html($homeUrl) }} class="fi-sidebar-header-logo-ctn">
                    <x-filament-panels::logo />
                </a>
            @else
                <div class="fi-sidebar-header-logo-ctn">
                    <x-filament-panels::logo />
                </div>
            @endif

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_LOGO_AFTER) }}
        </x-flux::sidebar.header>

        @if (filament()->hasTenancy() && filament()->hasTenantMenu())
            <x-filament-panels::tenant-menu />
        @endif

        @if (filament()->isGlobalSearchEnabled() && filament()->getGlobalSearchPosition() === \Filament\Enums\GlobalSearchPosition::Sidebar)
            <div>
                @livewire(Filament\Livewire\GlobalSearch::class)
            </div>
        @endif

        <x-flux::navlist class="fi-sidebar-nav">
            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_NAV_START) }}

            @foreach ($navigation as $group)
                @php
                    $isGroupActive = $group->isActive();
                    $isGroupCollapsible = $group->isCollapsible();
                    $groupIcon = $group->getIcon();
                    $groupItems = $group->getItems();
                    $groupLabel = $group->getLabel();
                    $groupExtraSidebarAttributeBag = $group->getExtraSidebarAttributeBag();
                @endphp

                <x-filament-panels::sidebar.group
                    :active="$isGroupActive"
                    :collapsible="$isGroupCollapsible"
                    :icon="$groupIcon"
                    :items="$groupItems"
                    :label="$groupLabel"
                    :attributes="\Filament\Support\prepare_inherited_attributes($groupExtraSidebarAttributeBag)"
                />
            @endforeach

            {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_NAV_END) }}
        </x-flux::navlist>

        @php
            $isAuthenticated = filament()->auth()->check();
            $hasDatabaseNotificationsInSidebar = filament()->hasDatabaseNotifications() && filament()->getDatabaseNotificationsPosition() === \Filament\Enums\DatabaseNotificationsPosition::Sidebar;
            $hasUserMenuInSidebar = filament()->hasUserMenu() && filament()->getUserMenuPosition() === \Filament\Enums\UserMenuPosition::Sidebar;
            $shouldRenderFooter = $isAuthenticated && ($hasDatabaseNotificationsInSidebar || $hasUserMenuInSidebar);
        @endphp

        @if ($shouldRenderFooter)
            <x-flux::spacer />

            <div class="fi-sidebar-footer">
                @if ($hasDatabaseNotificationsInSidebar)
                    @livewire(filament()->getDatabaseNotificationsLivewireComponent(), [
                        'lazy' => filament()->hasLazyLoadedDatabaseNotifications(),
                    ])
                @endif

                @if ($hasUserMenuInSidebar)
                    <x-filament-panels::user-menu />
                @endif
            </div>
        @endif

        {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::SIDEBAR_FOOTER) }}
    </x-flux::sidebar>

    <x-filament-actions::modals />
</div>
