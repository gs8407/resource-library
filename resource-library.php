<?php

/**
 * package Novolyze Resource Library
 */

/*
    Plugin Name: Novolyze Resource Library
    Description: Adds the Resource Library
    Version: 1.0.1
    Author: Mediavista
    Licence: GPLv2 or later
    Text Domain: resource-library
 */

if (!function_exists('add_action')) {
  die;
}

define('MY_PLUGIN_PATH_RESOURCES', plugin_dir_url(__FILE__));

class ResourceLibrary
{

  function __construct()
  {
    add_action('init', array($this, 'novolyze_resource_register'));
  }

  function register()
  {
    add_action('wp_enqueue_scripts', array($this, 'enqueue'));
  }

  function activate()
  {
    $this->novolyze_resource_register();
    flush_rewrite_rules();
  }


  function deactivate()
  {
    flush_rewrite_rules();
  }

  // Register Custom Post Type
  function novolyze_resource_register()
  {

    $labels = array(
      'name'                  => _x('Resource Libraries', 'Post Type General Name', 'resource-library'),
      'singular_name'         => _x('Resource Library', 'Post Type Singular Name', 'resource-library'),
      'menu_name'             => __('Resource Libraries', 'resource-library'),
      'name_admin_bar'        => __('Resource Libraries', 'resource-library'),
      'archives'              => __('Resource Libraries', 'resource-library'),
      'attributes'            => __('Resource Libraries Attributes', 'resource-library'),
      'parent_item_colon'     => __('Parent Item:', 'resource-library'),
      'all_items'             => __('All Items', 'resource-library'),
      'add_new_item'          => __('Add New Item', 'resource-library'),
      'add_new'               => __('Add New', 'resource-library'),
      'new_item'              => __('New Item', 'resource-library'),
      'edit_item'             => __('Edit Item', 'resource-library'),
      'update_item'           => __('Update Item', 'resource-library'),
      'view_item'             => __('View Item', 'resource-library'),
      'view_items'            => __('View Items', 'resource-library'),
      'search_items'          => __('Search Item', 'resource-library'),
      'not_found'             => __('Not found', 'resource-library'),
      'not_found_in_trash'    => __('Not found in Trash', 'resource-library'),
      'featured_image'        => __('Featured Image', 'resource-library'),
      'set_featured_image'    => __('Set featured image', 'resource-library'),
      'remove_featured_image' => __('Remove featured image', 'resource-library'),
      'use_featured_image'    => __('Use as featured image', 'resource-library'),
      'insert_into_item'      => __('Insert into item', 'resource-library'),
      'uploaded_to_this_item' => __('Uploaded to this item', 'resource-library'),
      'items_list'            => __('Items list', 'resource-library'),
      'items_list_navigation' => __('Items list navigation', 'resource-library'),
      'filter_items_list'     => __('Filter items list', 'resource-library'),
    );
    $args = array(
      'label'                 => __('Resource Library', 'resource-library'),
      'labels'                => $labels,
      'supports'              => array('title', 'editor', 'revisions', 'thumbnail'),
      'hierarchical'          => false,
      'public'                => true,
      'show_ui'               => true,
      'show_in_menu'          => true,
      'menu_position'         => 5,
      'menu_icon'             => 'dashicons-media-spreadsheet',
      'show_in_admin_bar'     => true,
      'show_in_nav_menus'     => true,
      'can_export'            => true,
      'has_archive'           => 'resource-library',
      'exclude_from_search'   => false,
      'publicly_queryable'    => true,
      'capability_type'       => 'page',
      'query_var' => false
    );
    register_post_type('resource-library', $args);
  }

  // Add CSS and JS
  function enqueue()
  {
    wp_enqueue_style('resource-library-styles', plugins_url('/assets/resource-library-main.css', __FILE__));
    wp_enqueue_script('resource-library-scripts', plugins_url('/assets/resource-library.js', __FILE__));
    wp_add_inline_script('search', 'ajax_url', admin_url('admin-ajax.php'));
  }

  function portfolios_shortcode()
  {

    $args = array(
      'post_type' => 'resource-library'
    );

    $the_query = new WP_Query($args);
?>
    <div class="resource-library-wrapper">
      <?php if ($the_query->have_posts()) :
        while ($the_query->have_posts()) : $the_query->the_post(); ?>
          <div class="<?php if ($the_query->current_post === 0 || $the_query->current_post % 6 === 0) echo "big";  ?>" style="background-image: url(<?php echo get_the_post_thumbnail_url(get_the_ID(), 'large'); ?>);">
            <a href="<?php echo get_the_permalink(); ?>"><?php the_title(); ?></a>
          </div>
      <?php endwhile;
      endif;
      wp_reset_postdata(); ?>
    </div>

<?php
  }
}

if (class_exists('ResourceLibrary')) {
  $ResourceLibrary = new ResourceLibrary();
  $ResourceLibrary->register();
}

// Set archive template
function resource_library_template($template)
{
  global $post;
  $plugin_root_dir = WP_PLUGIN_DIR . '/resource-library/';
  if (is_archive() && get_post_type($post) == 'resource-library') {
    $template = $plugin_root_dir . '/inc/templates/archive-resource-library.php';
  }

  return $template;
}
add_filter('archive_template', 'resource_library_template');

// Add Solutions category
function resource_library_topic_taxonomy()
{
  register_taxonomy(
    'resource_library_topic',  // The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
    'resource-library',             // post type name
    array(
      'hierarchical' => true,
      'label' => 'Topic', // display name
      'query_var' => true,
      'rewrite' => array(
        'slug' => 'topic-taxonomy',    // This controls the base slug that will display before each term
        'with_front' => false  // Don't display the category base before
      )
    )
  );
}
add_action('init', 'resource_library_topic_taxonomy');

// Add Industry category
function resource_library_type_taxonomy()
{
  register_taxonomy(
    'resource_library_type',  // The name of the taxonomy. Name should be in slug form (must not contain capital letters or spaces).
    'resource-library',             // post type name
    array(
      'hierarchical' => true,
      'label' => 'Type', // display name
      'query_var' => true,
      'rewrite' => array(
        'slug' => 'resource-library-type',    // This controls the base slug that will display before each term
        'with_front' => false  // Don't display the category base before
      )
    )
  );
}
add_action('init', 'resource_library_type_taxonomy');

// Activation
register_activation_hook(__FILE__, array($ResourceLibrary, 'activate'));

// Deactivation
register_activation_hook(__FILE__, array($ResourceLibrary, 'deactivate'));

/**
 * Filter
 */

add_action('wp_ajax_rl_filter', 'rl_filter_callback');
add_action('wp_ajax_nopriv_rl_filter', 'rl_filter_callback');

function rl_filter_callback()
{

  header("Content-Type: application/json");

  $result = array();

  if (!empty($_GET['search'])) {
    $search = sanitize_text_field($_GET['search']);
  }

  $paged = 1;
  $paged = sanitize_text_field($_GET['paginate']);

  $args = array(
    'post_type' => 'resource-library',
    'post_status' => 'publish',
    's' => $search,
    'posts_per_page' => 20,
    'paged' => $paged,
  );

  if (!empty($_GET['topic'])) {
    $topic = sanitize_text_field($_GET['topic']);
    $args['tax_query'][] = array(
      'taxonomy' => 'resource_library_topic',   // taxonomy name
      'field' => 'slug',           // term_id, slug or name
      'terms' => $topic,
    );
  }

  if (!empty($_GET['type'])) {
    $type = sanitize_text_field($_GET['type']);
    $args['tax_query'][] = array(
      'taxonomy' => 'resource_library_type',   // taxonomy name
      'field' => 'slug',           // term_id, slug or name
      'terms' => $type,
    );
  }

  $filter_query = new WP_Query($args);

  while ($filter_query->have_posts()) {
    $filter_query->the_post();
    $result[] = array(
      'title' => get_the_title(),
      'category' => get_the_terms($post->ID, 'resource_library_type'),
      'image' => get_the_post_thumbnail_url($post->ID, 'full'),
      'button_text' => get_field('button_text'),
      'button_url' => get_field('button_url'),
      'current_page' => $paged,
      'max' => $filter_query->max_num_pages,
    );
  }

  echo json_encode($result);

  wp_die();
};

if (function_exists('acf_add_local_field_group')) :

  acf_add_local_field_group(array(
    'key' => 'group_62542c365fe81',
    'title' => 'Resource Library',
    'fields' => array(
      array(
        'key' => 'field_62542cb90ee54',
        'label' => 'Button',
        'name' => '',
        'type' => 'tab',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'placement' => 'top',
        'endpoint' => 0,
      ),
      array(
        'key' => 'field_62542caf0ee53',
        'label' => 'Button text',
        'name' => 'button_text',
        'type' => 'text',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
        'prepend' => '',
        'append' => '',
        'maxlength' => '',
      ),
      array(
        'key' => 'field_62542cc80ee55',
        'label' => 'Button URL',
        'name' => 'button_url',
        'type' => 'url',
        'instructions' => '',
        'required' => 0,
        'conditional_logic' => 0,
        'wrapper' => array(
          'width' => '',
          'class' => '',
          'id' => '',
        ),
        'default_value' => '',
        'placeholder' => '',
      ),
    ),
    'location' => array(
      array(
        array(
          'param' => 'post_type',
          'operator' => '==',
          'value' => 'resource-library',
        ),
      ),
    ),
    'menu_order' => 0,
    'position' => 'normal',
    'style' => 'default',
    'label_placement' => 'top',
    'instruction_placement' => 'label',
    'hide_on_screen' => array(
      0 => 'permalink',
      1 => 'the_content',
      2 => 'excerpt',
      3 => 'discussion',
      4 => 'comments',
      5 => 'slug',
    ),
    'active' => true,
    'description' => '',
    'show_in_rest' => 0,
  ));

endif;
