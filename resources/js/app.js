import './bootstrap';
import Alpine from 'alpinejs';

// Inicializar Alpine.js
window.Alpine = Alpine;
Alpine.start();

// Importar Chart.js si estamos en una página de admin
if (document.querySelector('[data-admin-charts]')) {
    import('./admin-charts.js')
        .then(() => {
            console.log('📊 Módulo de gráficos admin cargado');
            // Disparar evento personalizado para notificar que Chart.js está listo
            window.dispatchEvent(new Event('chartjs-loaded'));
        })
        .catch(error => {
            console.error('❌ Error cargando Chart.js:', error);
        });
}
