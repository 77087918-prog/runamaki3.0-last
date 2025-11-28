<div x-data="{ 
    currentLocale: '{{ app()->getLocale() }}',
    locales: {
        'es': { name: 'Español', flag: '🇪🇸' },
        'qu': { name: 'Runasimi', flag: '🏔️' }
    },
    changeLanguage(locale) {
        fetch('/language/change', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content
            },
            body: JSON.stringify({ locale: locale })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                this.currentLocale = locale;
                // Recargar página para aplicar traducciones
                window.location.reload();
            }
        })
        .catch(error => console.error('Error:', error));
    }
}" class="relative">
    <!-- Botón selector -->
    <button 
        @click="$refs.dropdown.classList.toggle('hidden')"
        @click.away="$refs.dropdown.classList.add('hidden')"
        class="flex items-center space-x-2 px-3 py-2 rounded-lg hover:bg-gray-100 dark:hover:bg-gray-700 transition-colors"
        type="button"
    >
        <span x-text="locales[currentLocale].flag" class="text-xl"></span>
        <span x-text="locales[currentLocale].name" class="text-sm font-medium text-gray-700 dark:text-gray-300"></span>
        <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
        </svg>
    </button>

    <!-- Dropdown -->
    <div 
        x-ref="dropdown"
        class="hidden absolute right-0 mt-2 w-48 bg-white dark:bg-gray-800 rounded-lg shadow-lg border border-gray-200 dark:border-gray-700 z-50"
    >
        <template x-for="(data, code) in locales" :key="code">
            <button
                @click="changeLanguage(code)"
                :class="currentLocale === code ? 'bg-indigo-50 dark:bg-indigo-900/20' : ''"
                class="w-full flex items-center space-x-3 px-4 py-3 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors first:rounded-t-lg last:rounded-b-lg"
            >
                <span x-text="data.flag" class="text-2xl"></span>
                <div class="flex-1 text-left">
                    <div x-text="data.name" class="text-sm font-medium text-gray-900 dark:text-white"></div>
                    <div x-show="currentLocale === code" class="text-xs text-indigo-600 dark:text-indigo-400">
                        {{ __('app.current_language') }}
                    </div>
                </div>
                <svg x-show="currentLocale === code" class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                </svg>
            </button>
        </template>
    </div>
</div>
