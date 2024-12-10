<?php
$menuOptions = [
    'Coordinador' => [
        [
            'name' => 'Niños',
            'icon' => 'fa-solid fa-file',
            'submenus' => [
                ['name' => 'Subir Información', 'link' => 'ingresarInformacion.php'],
                ['name' => 'Modificar', 'link' => 'modificarInformacion.php']
            ]
        ],
        [
            'name' => 'Definir tiempos',
            'icon' => 'fa-solid fa-calendar-days',
            'link' => 'tiempos.php'
        ],
        [
            'name' => 'Reportes',
            'icon' => 'fas fa-file-alt',
            'link' => 'reportes.php'
        ],
        [
            'name' => 'Gestión',
            'icon' => 'fa-solid fa-cogs',
            'link' => 'usuarios.php'
        ]
    ],
    'Actualizar ram' => [
        [
            'name' => 'Niños',
            'icon' => 'fa-solid fa-file-circle-plus',
            'link' => 'modificarInformacion.php'
        ]
    ],
    'Admin' => [
        [
            'name' => 'Asignar Cartas',
            'icon' => 'fas fa-tachometer-alt',
            'link' => 'Cartas.php'
        ],
        [
            'name' => 'Informes',
            'icon' => 'fa-solid fa-file',
            'submenus' => [
                ['name' => 'Semestrales', 'link' => 'Semestral.php'],
                ['name' => 'Mensuales', 'link' => 'Mensuales.php'],
                ['name' => 'Anuales', 'link' => 'Anual.php']
            ]
        ],
        [
            'name' => 'Notificaciones',
            'icon' => 'fas fa-project-diagram',
            'link' => 'Notificaciones.php'
        ]
    ]
];
?>
