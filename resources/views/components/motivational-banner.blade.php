<!-- Desktop/Tablet banner (visible only on md+ screens) -->
<div style="display: none;" class="desktop-banner md:block bg-gradient-to-r {{ $dailyMessage['color'] }} text-slate-900 dark:text-white py-6 shadow-lg border-b border-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <p class="text-center text-2xl md:text-3xl font-extrabold tracking-tight leading-tight drop-shadow-sm">{{ $dailyMessage['text'] }}</p>
        <p class="text-center text-sm md:text-base text-slate-900 dark:text-white mt-2 font-semibold drop-shadow-sm">Consejo: revisa las habilidades populares o completa un trueque para ganar más Runas.</p>
    </div>
</div>

<!-- Mobile banner icon (visible only on small screens) -->
<div style="display: block;" class="mobile-banner-icon md:hidden bg-white border-b border-gray-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3 flex items-center justify-center">
        <button id="mobile-banner-toggle" type="button" aria-expanded="false" class="p-2 rounded-md bg-indigo-100 text-indigo-700 ring-2 ring-indigo-200 shadow-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </button>
    </div>
</div>

<!-- Mobile banner panel (hidden by default, toggleable) -->
<div id="mobile-banner-panel" style="display: none;" class="fixed inset-x-4 top-20 z-50 bg-gradient-to-r {{ $dailyMessage['color'] }} text-slate-900 dark:text-white rounded-lg shadow-2xl p-4 border border-white/20">
    <div class="flex items-start justify-between gap-3">
        <div class="flex-1">
            <p class="text-base font-bold drop-shadow-sm">{{ $dailyMessage['text'] }}</p>
            <p class="text-xs text-slate-900 dark:text-white mt-1 font-semibold drop-shadow-sm">Consejo: revisa las habilidades populares o completa un trueque para ganar más Runas.</p>
        </div>
        <button id="mobile-banner-close" type="button" class="flex-shrink-0 p-1.5 rounded-md bg-white/25 hover:bg-white/35 text-slate-900 dark:text-white transition shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
</div>

<script>
    // Mobile banner toggle functionality
    document.addEventListener('DOMContentLoaded', function() {
        const toggleButton = document.getElementById('mobile-banner-toggle');
        const closeButton = document.getElementById('mobile-banner-close');
        const bannerPanel = document.getElementById('mobile-banner-panel');
        
        if (toggleButton && bannerPanel) {
            toggleButton.addEventListener('click', function(e) {
                e.stopPropagation();
                const isHidden = bannerPanel.style.display === 'none';
                bannerPanel.style.display = isHidden ? 'block' : 'none';
                toggleButton.setAttribute('aria-expanded', isHidden ? 'true' : 'false');
            });
        }
        
        if (closeButton && bannerPanel) {
            closeButton.addEventListener('click', function(e) {
                e.stopPropagation();
                bannerPanel.style.display = 'none';
                toggleButton.setAttribute('aria-expanded', 'false');
            });
        }
        
        // Close panel when clicking outside
        if (bannerPanel) {
            document.addEventListener('click', function(e) {
                if (bannerPanel.style.display === 'block' && 
                    !bannerPanel.contains(e.target) && 
                    !toggleButton.contains(e.target)) {
                    bannerPanel.style.display = 'none';
                    toggleButton.setAttribute('aria-expanded', 'false');
                }
            });
            
            bannerPanel.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
    });
</script>

<style>
    /* Fallback CSS for responsive banner visibility */
    @media (max-width: 767px) {
        .desktop-banner {
            display: none !important;
        }
        .mobile-banner-icon {
            display: block !important;
        }
    }
    
    @media (min-width: 768px) {
        .desktop-banner {
            display: block !important;
        }
        .mobile-banner-icon {
            display: none !important;
        }
    }
</style>
