<?php
// Backup Script for Runa Maki Database
// Version: 1.0-stable
// Date: 2025-11-20

return [
    'database_info' => [
        'name' => 'runamaki3',
        'tables' => 20,
        'users' => 5,
        'habilidades' => 5,
        'backup_date' => '2025-11-20',
        'version' => 'v1.0-stable'
    ],
    
    'backup_commands' => [
        'export' => 'mysqldump -h hostname -u username -p database_name > backup_v1.0_stable.sql',
        'import' => 'mysql -h hostname -u username -p database_name < backup_v1.0_stable.sql',
        'artisan_backup' => 'php artisan db:backup --timestamp=v1.0-stable'
    ],
    
    'critical_tables' => [
        'users' => 'User accounts and profiles',
        'habilidades' => 'Skills and services offered',
        'trueques' => 'Exchange transactions',
        'valoraciones' => 'User ratings and reviews',
        'transacciones_puntos' => 'Points transactions history',
        'categorias' => 'Skill categories',
        'configuracion' => 'System configuration'
    ],
    
    'restoration_notes' => [
        'Always run migrations after restore',
        'Clear cache: php artisan cache:clear',
        'Recalculate reputations if needed',
        'Verify Railway deployment connection'
    ]
];