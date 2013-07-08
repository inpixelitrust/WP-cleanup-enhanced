<?php
/**
 * Plugin Name: Wordpress Clean up enhanced
 * Plugin URI: 
 * Description: My personal basic clean ups of wordpress admin including dashboard widgets, admin menu and sub menu, meta boxes, columns settings removal and security header junk tags cleanups. The plugin also adds twitter and facebook url in profil and other goodies from <a href= "http://wordpress.org/extend/plugins/selfish-fresh-start/" title="wordpress plugin page for Selfish fresh start"> the Selfish fresh start plugin </a> and an admin and login stylesheet to custom the admin, inpired by Valentin Brandt from <a href="http://www.geekeries.fr/snippet/personnaliser-interface-ui-wordpress-3-2/" title="Personnaliser l’interface de WordPress 3.2"> Geekeries</a>. Note that you might have to dig in this plugin code to comment or uncomment some lines to make it suite your needs.
 * Author: Stephanie Walter (akka Inpixelitrust)
 * Author URI: 
 * Version: 1.3
 */

 add_action('after_setup_theme','wpce_setup');
 
 function wpce_setup() {

/*** calling clean up fonctions, comment to disable a function----------------------------------------*/
	/* admin part cleanups */
	add_action('admin_menu','remove_dashboard_widgets'); // cleaning dashboard widgets
	add_action('admin_menu', 'delete_menu_items'); // deleting menu items from admin area
	add_action('admin_menu','customize_meta_boxes'); // remove some meta boxes from pages and posts edition page
	add_filter('manage_posts_columns', 'custom_post_columns'); // remove column entries from list of posts
	add_filter('manage_pages_columns', 'custom_pages_columns'); // remove column entries from list of page
	add_action('wp_before_admin_bar_render', 'wce_admin_bar_render' ); // clean up the admin bar
	add_action('widgets_init', 'unregister_default_widgets', 11); // remove widgets from the widget page

	/* selfish frshstart plugins code parts*/
	add_action('admin_notices','rynonuke_update_notification_nonadmins',1); // remove notification for enayone but admin
	add_action('pre_ping','rynonuke_self_pings'); // disable self-trackbacking
	add_action('admin_init','rynonuke_dolly'); // remove the hello dolly plugin
	add_filter('user_contactmethods','rynonuke_contactmethods',10,1);	// add facebook and twitter account to user profil

	/* Add admin custom actions styles*/
	add_action('login_head', 'style_my_login_please'); // add a custom css for the login form
	// add_action('admin_head', 'style_my_admin_please'); // add a custom css for the admin area
	
	/* admin page wp-login enhancements */
	add_filter( 'login_headerurl', 'custom_login_url' );// Make admin link point to the home of the site
	add_filter( 'login_headertitle', 'custom_login_title' );// Change alt title of admin logo to use blog name

	/* other clean ups */
	// add_action( 'init', 'wce_remove_l1on' ); remove the l10n.js script http://eligrey.com/blog/post/passive-localization-in-javascript

/**---------------------------------------------------------------------------------------------------*/

/***************** Security + header clean-ups ************************/
/** remove the wlmanifest (useless !!) */
	remove_action('wp_head', 'wlwmanifest_link');
	remove_action('wp_head', 'rsd_link');
	remove_action( 'wp_head', 'index_rel_link' ); // index link
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
	// remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.
	// remove_action( 'wp_head', 'feed_links_extra', 3 ); // Display the links to the extra feeds such as category feeds
	//remove_action( 'wp_head', 'feed_links', 2 ); // Display the links to the general feeds: Post and Comment Feed
	remove_action('wp_head','start_post_rel_link');
	remove_action('wp_head','adjacent_posts_rel_link_wp_head');
	remove_action('wp_head', 'wp_generator'); // remove WP version from header
	remove_action('wp_head','wp_shortlink_wp_head');
	remove_filter( 'the_content', 'capital_P_dangit' ); // Get outta my Wordpress codez dangit!
	remove_filter( 'the_title', 'capital_P_dangit' );
	remove_filter( 'comment_text', 'capital_P_dangit' );

	// removes detailed login error information for security
	add_filter('login_errors',create_function('$a', "return null;")); 
/**---------------------------------------------------------------------------------------------------*/
}


 /* Here come my different fonctions 
* 
* 
* */

/*** cleaning up the dashboard- ----------------------------------------*/
function remove_dashboard_widgets(){

	//remove_meta_box('dashboard_right_now','dashboard','core'); // right now overview box
	remove_meta_box('dashboard_incoming_links','dashboard','core'); // incoming links box
	//remove_meta_box('dashboard_quick_press','dashboard','core'); // quick press box
	remove_meta_box('dashboard_plugins','dashboard','core'); // new plugins box
	//remove_meta_box('dashboard_recent_drafts','dashboard','core'); // recent drafts box
	remove_meta_box('dashboard_recent_comments','dashboard','core'); // recent comments box
	remove_meta_box('dashboard_primary','dashboard','core'); // wordpress development blog box
	remove_meta_box('dashboard_secondary','dashboard','core'); // other wordpress news box
} 

/*----------------------------------------------------------------------*/

/* Remove some menus froms the admin area*/
function delete_menu_items() {
/*** Remove menu http://codex.wordpress.org/Function_Reference/remove_menu_page 
	syntaxe : remove_menu_page( $menu_slug )	**/
	//remove_menu_page('index.php'); // Dashboard
	//remove_menu_page('edit.php'); // Posts
	//remove_menu_page('upload.php'); // Media
	//remove_menu_page('link-manager.php'); // Links
	//remove_menu_page('edit.php?post_type=page'); // Pages
	//remove_menu_page('edit-comments.php'); // Comments
	//remove_menu_page('themes.php'); // Appearance
	//remove_menu_page('plugins.php'); // Plugins
	//remove_menu_page('users.php'); // Users
	//remove_menu_page('tools.php'); // Tools
	//remove_menu_page('options-general.php'); // Settings

/*** Remove submenu http://codex.wordpress.org/Function_Reference/remove_submenu_page 
	syntaxe : remove_submenu_page( $menu_slug, $submenu_slug ) **/
	//remove_submenu_page( 'edit.php', 'edit-tags.php?taxonomy=post_tag' ); // remove tags from edit
}

/*----------------------------------------------------------------------*/


/* remove some meta boxes from pages and posts -------------------------
feel free to comment / uncomment	*/

function customize_meta_boxes() {
/* Removes meta boxes from Posts */
	//remove_meta_box('postcustom','post','normal'); // custom fields metabox
	//remove_meta_box('trackbacksdiv','post','normal'); // trackbacks metabox 
	//remove_meta_box('commentstatusdiv','post','normal'); // comment status metabox 
	//remove_meta_box('commentsdiv','post','normal'); // comments	metabox 
	//remove_meta_box('postexcerpt','post','normal'); // post excerpts metabox 
	//remove_meta_box('authordiv','post','normal'); // author metabox 
	//remove_meta_box('revisionsdiv','post','normal'); // revisions	metabox 
	//remove_meta_box('tagsdiv-post_tag','post','normal'); // tags
	//remove_meta_box('slugdiv','post','normal'); // slug metabox 
	//remove_meta_box('categorydiv','post','normal'); // comments metabox
	//remove_meta_box('postimagediv','post','normal'); // featured image metabox
	//remove_meta_box('formatdiv','post','normal'); // format metabox 

/* Removes meta boxes from pages */	 
	//remove_meta_box('postcustom','page','normal'); // custom fields metabox
	//remove_meta_box('trackbacksdiv','page','normal'); // trackbacks metabox
	//remove_meta_box('commentstatusdiv','page','normal'); // comment status metabox 
	//remove_meta_box('commentsdiv','page','normal'); // comments	metabox 
	//remove_meta_box('authordiv','page','normal'); // author metabox 
	//remove_meta_box('revisionsdiv','page','normal'); // revisions	metabox 
	//remove_meta_box('postimagediv','page','side'); // featured image metabox
	//remove_meta_box('slugdiv','page','normal'); // slug metabox 

/* remove meta boxes for links **/
	//remove_meta_box('linkcategorydiv','link','normal');
	//remove_meta_box('linkxfndiv','link','normal');
	//remove_meta_box('linkadvanceddiv','link','normal');

}

/*-----------------------------------------------------------------------*/


/** removing parts from column ------------------------------------------*/
/* use the column id, if you need to hide more of them
syntaxe : unset($defaults['columnID']);	 */

/** remove column entries from posts **/
function custom_post_columns($defaults) {
	unset($defaults['comments']); // comments 
	unset($defaults['author']); // authors
	unset($defaults['tags']); // tag 
	//unset($defaults['date']); // date
	//unset($defaults['categories']); // categories		
	return $defaults;
}


/** remove column entries from pages **/
function custom_pages_columns($defaults) {
	unset($defaults['comments']); // comments 
	unset($defaults['author']); // authors
	unset($defaults['date']);	// date 
	return $defaults;
}

/*-----------------------------------------------------------------------**/


/** remove widgets from the widget page ------------------------------------*/
/* Credits : http://wpmu.org/how-to-remove-default-wordpress-widgets-and-clean-up-your-widgets-page/ 
uncomment what you want to remove	*/
 function unregister_default_widgets() {
	// unregister_widget('WP_Widget_Pages');
	// unregister_widget('WP_Widget_Calendar');
	// unregister_widget('WP_Widget_Archives');
	// unregister_widget('WP_Widget_Links');
	// unregister_widget('WP_Widget_Meta');
	// unregister_widget('WP_Widget_Search');
	// unregister_widget('WP_Widget_Text');
	// unregister_widget('WP_Widget_Categories');
	// unregister_widget('WP_Widget_Recent_Posts');
	// unregister_widget('WP_Widget_Recent_Comments');
	// unregister_widget('WP_Widget_RSS');
	// unregister_widget('WP_Widget_Tag_Cloud');
	//unregister_widget('WP_Nav_Menu_Widget');
	//unregister_widget('Twenty_Eleven_Ephemera_Widget');
 }



/****** removings items froms admin bars 
use the last part of the ID after "wp-admin-bar-" to add some menu to the list	exemple for comments : id="wp-admin-bar-comments" so the id to use is "comments"	***********/
function wce_admin_bar_render() {
global $wp_admin_bar;
	// $wp_admin_bar->remove_menu('comments'); //remove comments
	$wp_admin_bar->remove_menu('wp-logo'); //remove the whole wordpress logo, help etc part
}
/*-----------------------------------------------------------------------**/




/**	Other usefull cleanups from selfish fresh start plugin http://wordpress.org/extend/plugins/selfish-fresh-start/ --------------------*/

// remove update notifications for everybody except admin users
function rynonuke_update_notification_nonadmins() {
	if (!current_user_can('administrator')) 
		remove_action('admin_notices','update_nag',3);
}

// disable self-trackbacking
function rynonuke_self_pings( &$links ) {
		foreach ( $links as $l => $link )
				if ( 0 === strpos( $link, home_url() ) )
				unset($links[$l]);
}

// adios dolly : remove the hello dolly plugin
function rynonuke_dolly() {
	if (is_admin() && file_exists(WP_PLUGIN_DIR.'/hello.php'))
	@unlink(WP_PLUGIN_DIR.'/hello.php');
}
/*----------------------------------------------------------------------- **/



/** WordPress user profil cleanups	------------------------------------*/
	
/* remove the color scheme options */
	function admin_color_scheme() {
	global $_wp_admin_css_colors;
	$_wp_admin_css_colors = 0;
}

// add_action('admin_head', 'admin_color_scheme');

// rem/add user profile fields
function rynonuke_contactmethods($contactmethods) {
	unset($contactmethods['yim']);
	unset($contactmethods['aim']);
	unset($contactmethods['jabber']);
	$contactmethods['rynonuke_twitter']='Twitter';
	$contactmethods['rynonuke_facebook']='Facebook';
	return $contactmethods;
}


/*----------------------------------------------------------------------- **/
	
/*** Add a login stylesheet and a wordpress specific stylesheet------------
Special thanks to Valentin Brandt :	
http://www.geekeries.fr/snippet/personnaliser-interface-ui-wordpress-3-2/ 
stylesheets are in the plugin directory, you can change the content to make it suite your needs. You'll also find a logo.png file, to brand the login form using your personnal logo
-----------*/

function style_my_login_please() {	
/** stylesheet link for login **/
echo '<link rel="stylesheet" type="text/css" href="' .plugins_url( 'css/custom_login.css' , __FILE__ ). '"/>';
}

/** stylesheet link for admin **/
function style_my_admin_please() {
	echo '<link rel="stylesheet" type="text/css" href="' .plugins_url( 'css/custom_admin.css' , __FILE__ ).'"/>';
}
/*----------------------------------------------------------------------- **/

/** Custom admin login header link	------------------------------------*/
/* Make admin link point to the home of the site	*/
function custom_login_url() {
	return home_url( '/' );
}

/** Custom admin login header link alt text ------------------------------------*/
function custom_login_title() {
	return get_option( 'blogname' );
}


// Custom Backend Footer
function custom_admin_footer() {
	echo '<span id="footer-thankyou">Developed by <a href="http://pixeline.be" target="_blank">pixeline</a></span>.';
}

// adding it to the admin area
add_filter('admin_footer_text', 'bones_custom_admin_footer');

/*
	Customize Emails sent by Wordpress: FROM name + email
*/

add_filter('wp_mail_from','mail_from');
add_filter('wp_mail_from_name','mail_from_name');

define('MAIL_FROM','client@email.com');
define('MAIL_FROM_NAME','Client Name');

function mail_from() 
{	
	return MAIL_FROM ;
}

function mail_from_name() 
{
	return MAIL_FROM_NAME;
}


?>