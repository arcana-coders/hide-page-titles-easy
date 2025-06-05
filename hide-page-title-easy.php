<?php
/*
Plugin Name: Hide Page Titles (Global Toggle)
Description: Muestra u oculta los títulos de todas las páginas desde un panel propio.
Version: 1.2
Author: Arturo Carrillo
Author URI: https://tecnomata.com
License: GPL2
*/

// Al activar, agrega opción si no existe
register_activation_hook(__FILE__, function () {
    add_option('hide_titles_global', 0);
});

// Menú en el panel izquierdo de WordPress
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

// Renderiza el contenido del panel con estilo bonito
function render_hide_titles_page() {
    if (!current_user_can('manage_options')) return;

    // Si se envió el formulario
    if (isset($_POST['hide_titles_global'])) {
        update_option('hide_titles_global', 1);
    } else {
        update_option('hide_titles_global', 0);
    }

    $checked = get_option('hide_titles_global') ? 'checked' : '';

    echo '<div class="wrap" style="max-width:600px; background:#fff; padding:20px; border-radius:10px; box-shadow:0 2px 8px rgba(0,0,0,0.1);">';
    echo '<h1 style="margin-bottom:20px; font-size:24px;">🎛️ Ocultar Títulos de Páginas</h1>';
    echo '<form method="post">';
    echo '<label style="font-size:18px;"><input type="checkbox" name="hide_titles_global" value="1" ' . $checked . '> Ocultar todos los títulos de las páginas</label>';
    echo '<div style="margin-top:20px;">';
    submit_button('Guardar cambios');
    echo '</div>';
    echo '<p style="margin-top:30px; font-size:12px; color:#777;">Plugin desarrollado por <a href="https://tecnomata.com" target="_blank">Arturo Carrillo</a>.</p>';
    echo '</form>';
    echo '</div>';
}

// Filtro para ocultar el título si está activado
add_filter('the_title', function ($title, $id) {
    if (is_admin()) return $title;
    if (get_post_type($id) !== 'page') return $title;
    if (!in_the_loop() || !is_main_query()) return $title;

    $hide = get_option('hide_titles_global');
    return $hide ? '' : $title;
}, 10, 2);