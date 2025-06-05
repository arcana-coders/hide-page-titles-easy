<?php
/*
Plugin Name: Hide Page Titles (Global Toggle)
Description: Muestra u oculta los títulos de todas las páginas desde un panel propio sin afectar menús ni navegación.
Version: 1.3
Author: Arturo Carrillo
Author URI: https://tecnomata.com
License: GPL2
*/

// Al activar, agrega opción si no existe
register_activation_hook(__FILE__, function () {
    add_option('hide_titles_global', 0);
});

// Menú en el panel de administración
add_action('admin_menu', function () {
    add_menu_page(
        'Hide Page Titles',
        'Hide Titles',
        'manage_options',
        'hide-page-titles',
        'render_hide_titles_page',
        'dashicons-hidden',
        80
    );
});

// Página de configuración en el admin
function render_hide_titles_page() {
    if (!current_user_can('manage_options')) return;

    if (isset($_POST['submit'])) {
        $valor = isset($_POST['hide_titles_global']) ? 1 : 0;
        update_option('hide_titles_global', $valor);
        echo '<div class="updated"><p>Opciones guardadas.</p></div>';
    }

    $checked = get_option('hide_titles_global') ? 'checked' : '';

    echo '<div class="wrap" style="max-width:600px; background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">';
    echo '<h1 style="margin-bottom:20px; font-size:24px;">🎛️ Ocultar Títulos de Páginas</h1>';
    echo '<form method="post">';
    wp_nonce_field('guardar_opcion_ocultar_titulos', 'ocultar_titulos_nonce');
    echo '<label style="font-size:18px;"><input type="checkbox" name="hide_titles_global" value="1" ' . $checked . '> Ocultar todos los títulos de las páginas</label>';
    echo '<div style="margin-top:20px;">';
    echo '<input type="submit" name="submit" class="button button-primary" value="Guardar cambios">';
    echo '</div>';
    echo '<p style="margin-top:30px; font-size:12px; color:#777;">Plugin desarrollado por <a href="https://tecnomata.com" target="_blank">Arturo Carrillo</a>.</p>';
    echo '</form>';
    echo '</div>';
}

// Inserta CSS para ocultar títulos en páginas
add_action('wp_head', function () {
    if (get_option('hide_titles_global') && is_page()) {
        echo '<style>
            .page h1.entry-title,
            .page .page-title,
            .page header h1,
            .page .elementor-heading-title,
            .page h1 {
                display: none !important;
            }
        </style>';
    }
});
