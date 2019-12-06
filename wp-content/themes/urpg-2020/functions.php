<?php 
// Menu Theme Support
add_theme_support( 'menus' );

// Featured Image Theme Support
add_theme_support( 'post-thumbnails' );

// Creates Image Sizes
//add_image_size( 'example', 300, 250, true );

// Register main navigation menus
register_nav_menu('main_navigation', 'Main Navigation');
register_nav_menu('footer_menu', 'Footer Menu');

// Enqueue main scripts
function main_scripts() {
	wp_enqueue_style( 'main', '/dist/main.css' );
	
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'master', '/dist/master.js');
}

add_action( 'wp_enqueue_scripts', 'main_scripts' );

// Changes the [...] default readmore
add_filter( 'excerpt_more', function ( $more ) {
    return '…';
});

// Registers post types for each section of the InfoHub
$sections = [
	'general' => [
		'label' => 'General',
		'icon' => 'dashicons-admin-page'
	],
	'battles' => [
		'label' => 'Battles',
		'icon' => 'dashicons-editor-unlink'
	],
	'contests' => [
		'label' => 'Contests',
		'icon' => 'dashicons-buddicons-groups'
	],
	'stories' => [
		'label' => 'Stories',
		'icon' => 'dashicons-book-alt'
	],
	'art' => [
		'label' => 'Art',
		'icon' => 'dashicons-admin-customizer'
	],
	'national-park' => [
		'label' => 'National Park',
		'icon' => 'dashicons-admin-site-alt2'
	],
	'morphic' => [
		'label' => 'Morphic',
		'icon' => 'dashicons-palmtree'
	],
];

$position = 10;
foreach ($sections as $section => $value) {
	register_post_type($section, [
		'label' => __($value['label']),
		'public' => true,
		'show_ui' => true,
		'show_in_rest' => true,
		'menu_icon' => $value['icon'],
		'menu_position' => $position,
		'supports' => array(
			'title',
			'editor',
			'revisions',
			'thumbnail'
		)
	]);
}


// Removes Pages
add_action('admin_init', function(){
	remove_menu_page('edit.php?post_type=page');
	remove_menu_page('edit.php');
});


// The below group of functions completely removes comments and comment support

add_action('admin_init', function () {
    // Redirect any user trying to access comments page
    global $pagenow;
    
    if ($pagenow === 'edit-comments.php') {
        wp_redirect(admin_url());
        exit;
    }
    // Remove comments metabox from dashboard
    remove_meta_box('dashboard_recent_comments', 'dashboard', 'normal');
    // Disable support for comments and trackbacks in post types
    foreach (get_post_types() as $post_type) {
        if (post_type_supports($post_type, 'comments')) {
            remove_post_type_support($post_type, 'comments');
            remove_post_type_support($post_type, 'trackbacks');
        }
    }
});
// Close comments on the front-end
add_filter('comments_open', '__return_false', 20, 2);
add_filter('pings_open', '__return_false', 20, 2);

// Hide existing comments
add_filter('comments_array', '__return_empty_array', 10, 2);

// Remove comments page in menu
add_action('admin_menu', function () {
    remove_menu_page('edit-comments.php');
});
// Remove comments links from admin bar
add_action('init', function () {
    if (is_admin_bar_showing()) {
        remove_action('admin_bar_menu', 'wp_admin_bar_comments_menu', 60);
    }
});

?>