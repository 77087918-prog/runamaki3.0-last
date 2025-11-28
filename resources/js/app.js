import './bootstrap';

// Importar Chart.js si estamos en una página de admin
if (document.querySelector('[data-admin-charts]')) {
    import('./admin-charts.js').then(() => {
        console.log('📊 Módulo de gráficos admin cargado');
    });
}
