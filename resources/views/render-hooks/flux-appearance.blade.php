@if ($injectAppearance)
    {{-- Pre-sync: copy Filament's persisted theme into Flux's storage key BEFORE @fluxAppearance runs, --}}
    {{-- so Flux's boot script reads the correct value on first paint and avoids a flash. --}}
    <script>
        (function () {
            try {
                var t = localStorage.getItem('theme');
                if (t === 'light' || t === 'dark' || t === 'system') {
                    localStorage.setItem('flux.appearance', t);
                }
            } catch (e) {}
        })();
    </script>

    @fluxAppearance

    {{-- Two-way bridge between Filament's theme switcher and Flux.applyAppearance. --}}
    <script>
        (function () {
            function mirror(next) {
                if (next !== 'light' && next !== 'dark' && next !== 'system') return;
                try { localStorage.setItem('flux.appearance', next); } catch (e) {}
                if (window.Flux && typeof window.Flux.applyAppearance === 'function') {
                    window.Flux.applyAppearance(next);
                }
            }

            window.addEventListener('theme-changed', function (event) {
                mirror(event && event.detail);
            });

            window.addEventListener('storage', function (event) {
                if (event.key === 'theme' && event.newValue) mirror(event.newValue);
            });
        })();
    </script>
@endif
