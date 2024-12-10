<?php
$menuOptions = [
    'Coordinador' => [
        [
            'name' => 'Niños',
            'icon' => 'fa-solid fa-file-circle-plus',
            'submenus' => [
                ['name' => 'Subir Información', 'link' => 'ingresarInformacion.php'],
                ['name' => 'Modificar', 'link' => 'modificarInformacion.php'],
                ['name'=> 'Asignar', 'link' => 'asignarAdministradores.php']
            ]
        ],
        [
            'name' => 'Definir tiempos',
            'icon' => 'fa-solid fa-calendar-days',
            'link' => 'tiempos.php'
        ],
        [
            'name' => 'Informes',
            'icon' => 'fa-solid fa-file-alt',
            'submenus' => [
                ['name' => 'Semestrales', 'link' => 'Semestral.php'],
                ['name' => 'Mensuales', 'link' => 'Mensuales.php'],
                ['name' => 'Anuales', 'link' => 'Anual.php']
            ]
        ],
        [
            'name' => 'Gestión',
            'icon' => 'fa-solid fa-cogs',
            'link' => 'usuarios.php'
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
    ],
    'Correspondencia' => [
    [
            'name' => 'Gestión de Correspondencia',
            'icon' => 'fa-solid fa-envelope',
            'link' => 'gestionCorrespondencia.php'       
    ]
    
    ],
    'Actualizar ram' => [
    [
            'name' => 'Actualizacion',
            'icon' => 'fa-solid fa-envelope',
            'link' => 'modificarInformacion.php'       
    ]
    ]
];
?>


