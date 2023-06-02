<?php

namespace WP\Notifications;

use WP_Admin_Bar;
use WP\Notifications\REST;

/**
 * Activation hook function of the WP Notification plugin.
 */
function activation_hook() {
	require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-activator.php';
	Activator::activate();
}

register_activation_hook( __FILE__, '\WP\Notifications\activation_hook' );

/**
 * Uninstall hook function of the WP Notification plugin.
 */
function uninstall_hook() {
	require_once WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/includes/class-uninstaller.php';
	Uninstaller::uninstall();
}

register_uninstall_hook( __FILE__, '\WP\Notifications\uninstall_hook' );

/**
 * REST API initialization hook of the WP Notification plugin.
 */
function register_routes() {
	$channel_controller      = new REST\Channel_Controller();
	$notification_controller = new REST\Notification_Controller();
	$subscription_controller = new REST\Subscription_Controller();

	$channel_controller->register_routes();
	$notification_controller->register_routes();
	$subscription_controller->register_routes();

}

add_action( 'rest_api_init', '\WP\Notifications\register_routes' );

/**
 * Adds WP Notifications icon after the user avatar in the top admin bar in the "secondary" position
 *
 * @param WP_Admin_Bar $wp_admin_bar Toolbar instance.
 */
function admin_bar_item( WP_Admin_Bar $wp_admin_bar ) {
	if ( ! is_user_logged_in() ) {
		return;
	}

	/**
	 * This is the same HTML as the `src/scripts/components/NotificationHub.js`
	 * If this is changed that file must also be updated.
	 */
	$notification_hub = sprintf(
		'<aside id="wp-notifications-hub"><div class="hub-wrapper"><h2 class="screen-reader-text">%s</h2></div></aside>',
		__( 'Notifications', 'wp-feature-notifications' )
	);

	/**
	 * This is the same HTML as the `src/scripts/components/NotificationHubIcon.js`
	 * If this is changed that file must also be updated.
	 */
	$notification_hub_icon = sprintf(
		'<div class="notifications"><button class="hub-icon" aria-haspopup="menu"><span class="ab-icon dashicons dashicons-bell" aria-hidden="true"></span><span class="ab-label screen-reader-text">%s</span><span class="unread-dot"></span></button></div>',
		__( 'Notifications', 'wp-feature-notifications' )
	);

	$args = array(
		'id'     => 'wp-notifications-hub',
		'parent' => 'top-secondary',
		'title'  => $notification_hub_icon,
		'meta'   => array(
			'tabindex' => 0,
			'html'     => $notification_hub,
		),
	);
	$wp_admin_bar->add_node( $args );
}

add_action( 'admin_bar_menu', '\WP\Notifications\admin_bar_item', 1 );

/**
 * Register and enqueue a wp-notifications scripts and stylesheet in WordPress admin.
 */
function enqueue_admin_assets() {
	if ( ! is_user_logged_in() ) {
		return;
	}

	/* Load styles */
	wp_register_style( 'wp_notifications', WP_FEATURE_NOTIFICATION_PLUGIN_DIR_URL . '/build/wp-notifications.css', array(), WP_FEATURE_NOTIFICATION_PLUGIN_VERSION );
	wp_enqueue_style( 'wp_notifications' );

	/* Load scripts */
	$asset = include WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/build/wp-notifications.asset.php';
	wp_register_script( 'wp_notifications', WP_FEATURE_NOTIFICATION_PLUGIN_DIR_URL . '/build/wp-notifications.js', $asset['dependencies'], WP_FEATURE_NOTIFICATION_PLUGIN_VERSION, true );
	wp_enqueue_script( 'wp_notifications' );
}


add_action( 'wp_enqueue_scripts', '\WP\Notifications\enqueue_admin_assets', 0 );
add_action( 'admin_enqueue_scripts', '\WP\Notifications\enqueue_admin_assets', 0 );
