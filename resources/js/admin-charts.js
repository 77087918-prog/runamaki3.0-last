import { Chart, registerables } from 'chart.js';

// Registrar todos los componentes de Chart.js
Chart.register(...registerables);

// Exportar Chart para uso global
window.Chart = Chart;

console.log('✅ Chart.js cargado desde módulo local');
