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
		'icon' => 'dashicons-editor-unlink',
		'section_staff_label' => 'Senior Referee',
		'section_staff_slug' => 'senior-referee'
	],
	'contests' => [
		'label' => 'Contests',
		'icon' => 'dashicons-buddicons-groups',
		'section_staff_label' => 'Chief Judge',
		'section_staff_slug' => 'chief-judge'
	],
	'stories' => [
		'label' => 'Stories',
		'icon' => 'dashicons-book-alt',
		'section_staff_label' => 'Lead Grader',
		'section_staff_slug' => 'lead-grader'
	],
	'art' => [
		'label' => 'Art',
		'icon' => 'dashicons-admin-customizer',
		'section_staff_label' => 'Expert Curator',
		'section_staff_slug' => 'expert-curator'
	],
	'national_park' => [
		'label' => 'National Park',
		'icon' => 'dashicons-admin-site-alt2',
		'section_staff_label' => 'Elite Ranger',
		'section_staff_slug' => 'elite-ranger'
	],
	'morphic' => [
		'label' => 'Morphic',
		'icon' => 'dashicons-palmtree',
		'section_staff_label' => 'Elder Arbiter',
		'section_staff_slug' => 'elder-arbiter'
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
		'taxonomies' => array('category'),
		'supports' => array(
			'title',
			'editor',
			'revisions',
			'thumbnail'
		),
	]);
	
	add_role( $value['section_staff_slug'], __($value['section_staff_label']), [
		'read',
		'read_' . $section,
		'read_private_' . $section,
		'edit_' . $section,
		'edit_others_' . $section,
		'edit_published_' . $section,
		'publish_' . $section,
		'delete_others_' . $section,
		'delete_private_' . $section,
		'delete_published_' . $section,
	]);
};

// Removes default user roles
add_action('admin_menu', function() {
    global $wp_roles;
    $roles_to_remove = array('subscriber', 'contributor', 'author', 'editor');
    foreach ($roles_to_remove as $role) {
        if (isset($wp_roles->roles[$role])) {
            $wp_roles->remove_role($role);
        }
    }
});

// Removes Posts & Pages
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