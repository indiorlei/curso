<?php
/*
Plugin Name: Curso
Plugin URI: http://www.indiorlei.com
Description: 
Version: 1.0
Author: Indiorlei de Oliveira
Author URI: http://www.indiorlei.com
License: GPLv2
*/
add_action( 'init', 'create_curso' );
function create_curso() {
register_post_type( 'curso',
array(
'labels' => array(
'name' => 'Cursos',
'singular_name' => 'Curso',
'add_new' => 'Adicionar Novo',
'add_new_item' => 'Adicionar Novo Curso',
'edit' => 'Editar',
'edit_item' => 'Editar Curso',
'new_item' => 'Novo Curso',
'view' => 'Ver',
'view_item' => 'Ver Curso',
'search_items' => 'Procurar Cursos',
'not_found' => 'Nenhum Curso encontrado',
'not_found_in_trash' => 'Nenhum Curso encontrado na Lixeira',
'parent' => 'Curso Similar' 
),
'public' => true,
'show_ui' => true,
'show_in_menu' => true,
'capability_type' => 'post',
'map_meta_cap' => true,
'menu_position' => 15,
'supports' => array( 'title', 'editor', 'comments', 'thumbnail', 'custom-fields' ),
'taxonomies' => array( 'curso-categories' ),
'has_archive' => true
)
);
}
add_action( 'init', 'create_my_taxonomies', 0 );
function create_my_taxonomies() {
register_taxonomy(
'curso-categories',
'curso',
array(
'labels' => array(
'name' => 'Categoria do Curso',
'add_new_item' => 'Adicionar Nova Categoria do Curso',
'new_item_name' => "Nova Categoria do Curso"
),
'show_ui' => true,
'show_tagcloud' => false,
'hierarchical' => true
)
);
}
add_action( 'admin_init', 'my_admin' );
function my_admin() {
add_meta_box( 'curso_meta_box',
'Detalhes do Curso',
'display_curso_meta_box',
'curso', 'normal', 'high'
);
}
function display_curso_meta_box( $curso ) {
// Retrieve the Curso related information
$curso_type = esc_html( get_post_meta( $curso->ID, 'curso_type', true ) );
$curso_target = esc_html( get_post_meta( $curso->ID, 'curso_target', true ) );
$curso_duration = esc_html( get_post_meta( $curso->ID, 'curso_duration', true ) );
$curso_description = esc_html( get_post_meta( $curso->ID, 'curso_description', true ) );
$curso_legislation = esc_html( get_post_meta( $curso->ID, 'curso_legisltion', true ) );
?>
<table>
<tr>
<td style="width: 100%">Tipo de Curso</td>
<td><input type="text" size="80" name="curso_type" value="<?php echo $curso_type; ?>" /></td>
</tr>
<tr>
<td style="width: 100%">Alvo do Curso</td>
<td><input type="text" size="80" name="curso_target" value="<?php echo $curso_target; ?>" /></td>
</tr>
<tr>
<td style="width: 100%">Duração do Curso</td>
<td><input type="text" size="80" name="curso_duration" value="<?php echo $curso_duration; ?>" /></td>
</tr>
<tr>
<td style="width: 100%">Descrição do Curso</td>
<td><textarea name="curso_description" id="curso_description" cols="50" rows="1"><?php echo $curso_description; ?></textarea></td>
</tr>
<tr>
<td style="width: 100%">Legislação do Curso</td>
<td><input type="text" size="80" name="curso_legislation" value="<?php echo $curso_legislation; ?>" /></td>
</tr>
</table>
<?php
}
add_action( 'save_post', 'add_curso_fields', 10, 2 );

function add_curso_fields( $curso_id, $curso ) {
// Check post type for our Cursos
if ( $curso->post_type == 'curso' ) {
// Store data in post meta table if present in post data
if ( isset( $_POST['curso_type'] ) && $_POST['curso_type'] != '' ) {
update_post_meta( $curso_id, 'curso_type', $_POST['curso_type'] );
}
if ( isset( $_POST['curso_target'] ) && $_POST['curso_target'] != '' ) {
update_post_meta( $curso_id, 'curso_target', $_POST['curso_target'] );
}
if ( isset( $_POST['curso_duration'] ) && $_POST['curso_duration'] != '' ) {
update_post_meta( $curso_id, 'curso_duration', $_POST['curso_duration'] );
}
if ( isset( $_POST['curso_description'] ) && $_POST['curso_description'] != '' ) {
update_post_meta( $curso_id, 'curso_description', $_POST['curso_description'] );
}
if ( isset( $_POST['curso_legislation'] ) && $_POST['curso_legislation'] != '' ) {
update_post_meta( $curso_id, 'curso_legislation', $_POST['curso_legislation'] );
}
}
}
add_filter( 'manage_edit-curso_columns', 'my_columns' );
function my_columns( $columns ) {
$columns['curso-type'] = 'Type';
$columns['curso-target'] = 'Target';
$columns['curso-duration'] = 'Duration';
unset( $columns['comments'] );
return $columns;
}
add_action( 'manage_posts_custom_column', 'populate_columns' );
function populate_columns( $column ) {
if ( 'curso-type' == $column ) {
$curso_type = esc_html( get_post_meta( get_the_ID(), 'curso_type', true ) );
echo $curso_type;
}
elseif ( 'curso-target' == $column ) {
$curso_target = esc_html( get_post_meta( get_the_ID(), 'curso_target', true ) );
echo $curso_target;
}
elseif ( 'curso-duration' == $column ) {
$curso_duration = esc_html( get_post_meta( get_the_ID(), 'curso_duration', true ) );
echo $curso_duration;
}
} 
add_action( 'restrict_manage_posts', 'my_filter_list' );
function my_filter_list() {
$screen = get_current_screen();
global $wp_query;
if ( $screen->post_type == 'curso' ) {
wp_dropdown_categories( array(
'show_option_all' => 'Show All Curso Categories',
'taxonomy' => 'curso-categories',
'name' => 'curso-categories',
'orderby' => 'name',
'selected' => ( isset( $wp_query->query['curso-categories'] ) ? $wp_query->query['curso-categories'] : '' ),
'hierarchical' => false,
'depth' => 3,
'show_count' => false,
'hide_empty' => true,
) );
}
}
add_filter( 'parse_query','perform_filtering' );
function perform_filtering( $query ) {
$qv = &$query->query_vars;
if ( ( $qv['curso-categories'] ) && is_numeric( $qv['curso-categories'] ) ) {
$term = get_term_by( 'id', $qv['curso-categories'], 'curso-categories' );
$qv['curso-categories'] = $term->slug;
}
}
add_filter( 'template_include', 'include_template_function', 1 );
function include_template_function( $template_path ) {
if ( get_post_type() == 'curso' ) {
if ( is_single() ) {
// checks if the file exists in the theme first,
// otherwise serve the file from the plugin
if ( $theme_file = locate_template( array ( 'single-curso.php' ) ) ) {
$template_path = $theme_file;
} else {
$template_path = plugin_dir_path( __FILE__ ) . '/single-curso.php';
}
}
elseif ( is_archive() ) {
if ( $theme_file = locate_template( array ( 'archive-curso.php' ) ) ) {
$template_path = $theme_file;
} else { $template_path = plugin_dir_path( __FILE__ ) . '/archive-curso.php';
}
}
}
return $template_path;
}
?>