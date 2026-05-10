@if ($injectScripts)
    @fluxScripts
@endif

<script>
    // Bridge: Filament's `$store.sidebar` (Alpine store) and Flux's
    // `<ui-sidebar>` custom element use independent open/close state.
    // When the panel uses Flux for the sidebar shell, Filament's topbar
    // buttons (`fi-topbar-open-sidebar-btn`, etc.) flip the Filament
    // store but never reach the Flux element. This bridge listens to
    // store changes and dispatches Flux's documented
    // `flux-sidebar-toggle` event so the visual state stays in sync.
    document.addEventListener('alpine:init', () => {
        setTimeout(() => {
            const store = window.Alpine?.store?.('sidebar');
            if (! store || typeof window.Alpine.effect !== 'function') {
                return;
            }

            const sidebar = () => document.querySelector('ui-sidebar');

            const isFluxCollapsed = () => {
                const el = sidebar();
                if (! el) return null;

                if (window.innerWidth >= 1024) {
                    return el.hasAttribute('data-flux-sidebar-collapsed-desktop');
                }

                return el.hasAttribute('data-flux-sidebar-collapsed-mobile');
            };

            const dispatchToggle = () => {
                document.dispatchEvent(new Event('flux-sidebar-toggle'));
            };

            // Filament store -> Flux element.
            window.Alpine.effect(() => {
                const wantOpen = store.isOpen;
                const fluxCollapsed = isFluxCollapsed();

                if (fluxCollapsed === null) return;

                if (wantOpen === fluxCollapsed) {
                    dispatchToggle();
                }
            });

            // Flux element -> Filament store. Watch the data-attrs Flux
            // toggles when the user clicks the backdrop or a
            // `<ui-sidebar-toggle>` element.
            const observer = new MutationObserver(() => {
                const fluxCollapsed = isFluxCollapsed();
                if (fluxCollapsed === null) return;

                const wantOpen = ! fluxCollapsed;
                if (store.isOpen !== wantOpen) {
                    wantOpen ? store.open() : store.close();
                }
            });

            const attach = () => {
                const el = sidebar();
                if (! el) return;
                observer.disconnect();
                observer.observe(el, {
                    attributes: true,
                    attributeFilter: [
                        'data-flux-sidebar-collapsed-mobile',
                        'data-flux-sidebar-collapsed-desktop',
                    ],
                });
            };

            attach();
            document.addEventListener('livewire:navigated', attach);
        }, 0);
    });
</script>
