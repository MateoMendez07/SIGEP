<?php 
// vista/sidebar.php
require "../Ajax/menuController.php"; // Asegúrate de que este archivo maneje los elementos del menú correctamente.
?>
<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <a href="#" class="brand-link">
        <img src="../Recursos/f.png" alt="Foci Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">FOCI</span>
    </a>
    <div class="sidebar">
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="true">
                <?php foreach ($menuItems as $item): ?>
                    <li class="nav-item <?= isset($item['submenus']) ? 'has-treeview' : '' ?>">
                        <a href="<?= $item['link'] ?? '#' ?>" class="nav-link">
                            <i class="nav-icon <?= $item['icon'] ?>"></i>
                            <p>
                                <?= $item['name'] ?>
                                <?php if (isset($item['submenus'])): ?>
                                    <i class="right fas fa-angle-left"></i>
                                <?php endif; ?>
                            </p>
                        </a>
                        <?php if (isset($item['submenus'])): ?>
                            <ul class="nav nav-treeview">
                                <?php foreach ($item['submenus'] as $submenu): ?>
                                    <li class="nav-item">
                                        <a href="<?= $submenu['link'] ?>" class="nav-link">
                                            <i class="far fa-circle nav-icon"></i>
                                            <p><?= $submenu['name'] ?></p>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </nav>
    </div>
</aside>

