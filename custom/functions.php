<?php
/**
 * custom functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package custom
 */

if ( ! defined( '_S_VERSION' ) ) {
	// Replace the version number of the theme on each release.
	define( '_S_VERSION', '1.0.0' );
}

/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which
 * runs before the init hook. The init hook is too late for some features, such
 * as indicating support for post thumbnails.
 */
function custom_setup() {
	/*
		* Make theme available for translation.
		* Translations can be filed in the /languages/ directory.
		* If you're building a theme based on custom, use a find and replace
		* to change 'custom' to the name of your theme in all the template files.
		*/
	load_theme_textdomain( 'custom', get_template_directory() . '/languages' );

	// Add default posts and comments RSS feed links to head.
	add_theme_support( 'automatic-feed-links' );

	/*
		* Let WordPress manage the document title.
		* By adding theme support, we declare that this theme does not use a
		* hard-coded <title> tag in the document head, and expect WordPress to
		* provide it for us.
		*/
	add_theme_support( 'title-tag' );

	/*
		* Enable support for Post Thumbnails on posts and pages.
		*
		* @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		*/
	add_theme_support( 'post-thumbnails' );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus(
		array(
			'menu-1' => esc_html__( 'Primary', 'custom' ),
		)
	);

	/*
		* Switch default core markup for search form, comment form, and comments
		* to output valid HTML5.
		*/
	add_theme_support(
		'html5',
		array(
			'search-form',
			'comment-form',
			'comment-list',
			'gallery',
			'caption',
			'style',
			'script',
		)
	);

	// Set up the WordPress core custom background feature.
	add_theme_support(
		'custom-background',
		apply_filters(
			'custom_custom_background_args',
			array(
				'default-color' => 'ffffff',
				'default-image' => '',
			)
		)
	);

	// Add theme support for selective refresh for widgets.
	add_theme_support( 'customize-selective-refresh-widgets' );

	/**
	 * Add support for core custom logo.
	 *
	 * @link https://codex.wordpress.org/Theme_Logo
	 */
	add_theme_support(
		'custom-logo',
		array(
			'height'      => 250,
			'width'       => 250,
			'flex-width'  => true,
			'flex-height' => true,
		)
	);
}
add_action( 'after_setup_theme', 'custom_setup' );

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width
 */
function custom_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'custom_content_width', 640 );
}
add_action( 'after_setup_theme', 'custom_content_width', 0 );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function custom_widgets_init() {
	register_sidebar(
		array(
			'name'          => esc_html__( 'Sidebar', 'custom' ),
			'id'            => 'sidebar-1',
			'description'   => esc_html__( 'Add widgets here.', 'custom' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h2 class="widget-title">',
			'after_title'   => '</h2>',
		)
	);
}
add_action( 'widgets_init', 'custom_widgets_init' );

/**
 * Enqueue scripts and styles.
 */
function custom_scripts() {
	wp_enqueue_style( 'custom-style', get_stylesheet_uri(), array(), _S_VERSION );
	wp_style_add_data( 'custom-style', 'rtl', 'replace' );

	wp_enqueue_script( 'custom-navigation', get_template_directory_uri() . '/js/navigation.js', array(), _S_VERSION, true );

	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}
}
add_action( 'wp_enqueue_scripts', 'custom_scripts' );

/**
 * Implement the Custom Header feature.
 */
require get_template_directory() . '/inc/custom-header.php';

/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Functions which enhance the theme by hooking into WordPress.
 */
require get_template_directory() . '/inc/template-functions.php';

/**
 * Customizer additions.
 */
require get_template_directory() . '/inc/customizer.php';

/**
 * Load Jetpack compatibility file.
 */
if ( defined( 'JETPACK__VERSION' ) ) {
	require get_template_directory() . '/inc/jetpack.php';
}

function create_custom_product_post_type() {
    $labels = array(
        'name' => 'Products',
        'singular_name' => 'Custom Product',
        'add_new' => 'Add New',
        'add_new_item' => 'Add New Custom Product',
        'edit_item' => 'Edit Custom Product',
        'new_item' => 'New Custom Product',
        'view_item' => 'View Custom Product',
        'search_items' => 'Search Custom Products',
        'not_found' => 'No custom products found',
        'not_found_in_trash' => 'No custom products found in Trash',
    );

    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-cart', // You can change this icon.
        'supports' => array('title', 'editor', 'thumbnail', 'excerpt', 'custom-fields', 'color'),
        'rewrite' => array('slug' => 'custom-products'), // You can customize the slug.
    );

    register_post_type('custom-product', $args);

    // Register a custom taxonomy for categories
    $category_labels = array(
        'name' => 'Product Categories',
        'singular_name' => 'Product Category',
        'search_items' => 'Search Categories',
        'all_items' => 'All Categories',
        'parent_item' => 'Parent Category',
        'parent_item_colon' => 'Parent Category:',
        'edit_item' => 'Edit Category',
        'update_item' => 'Update Category',
        'add_new_item' => 'Add New Category',
        'new_item_name' => 'New Category Name',
        'menu_name' => 'Categories',
    );

    $category_args = array(
        'hierarchical' => true, // Set to true for category-like behavior.
        'labels' => $category_labels,
        'show_ui' => true,
        'show_admin_column' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'product-category'), // Customize the slug.
    );

    register_taxonomy('product-category', 'custom-product', $category_args);
}
add_action('init', 'create_custom_product_post_type');

function add_custom_boxes() {
    add_meta_box(
        'wporg_box_id',
        'Price',
        'custom_box_html',
        'custom-product' // Specify the post type here
    );

    add_meta_box(
        'wporg_box_id_second',
        'Color',
        'custom_box_html_second',
        'custom-product' // Specify the post type here
    );
}
add_action('add_meta_boxes', 'add_custom_boxes');

function custom_box_html($post) {
    // Add a name attribute to the input field
    $price = get_post_meta($post->ID, '_custom_product_price', true);
    ?>
    <label for="custom_product_price">Enter Price</label>
    <input type="text" name="custom_product_price" id="custom_product_price" value="<?php echo esc_attr($price); ?>">
    <?php
}

function custom_box_html_second($post) {
    $value = get_post_meta($post->ID, '_custom_product_color', true); // Use a unique meta key

    // Define your color options array (ideally in your functions.php or another suitable file)
    $custom_product_colors = array(
        'black' => 'Black',
        'blue' => 'Blue',
        'red' => 'Red',
        'green' => 'Green',
        'yellow' => 'Yellow',
        'purple' => 'Purple',
        'brown' => 'Brown',
        'grey' => 'Grey',
    );

    ?>
    <label for="custom_product_color">Color</label>
    <select name="custom_product_color" id="custom_product_color" class="postbox">
        <option value="">Select something...</option>
        <?php
        // Generate color options dynamically
        foreach ($custom_product_colors as $color_key => $color_label) {
            echo '<option value="' . esc_attr($color_key) . '" ' . selected($value, $color_key, false) . '>' . esc_html($color_label) . '</option>';
        }
        ?>
    </select>
    <?php
}
// In your functions.php or another suitable file
$custom_product_colors = array(
    'black' => 'Black',
    'blue' => 'Blue',
    'red' => 'Red',
    'green' => 'Green',
    'yellow' => 'Yellow',
    'purple' => 'Purple',
    'brown' => 'Brown',
    'grey' => 'Grey',
);



function save_custom_product_fields($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    if (isset($_POST['custom_product_price'])) {
        update_post_meta($post_id, '_custom_product_price', sanitize_text_field($_POST['custom_product_price']));
    }

    if (isset($_POST['custom_product_color'])) {
        update_post_meta($post_id, '_custom_product_color', sanitize_text_field($_POST['custom_product_color']));
    }
}
add_action('save_post', 'save_custom_product_fields');

