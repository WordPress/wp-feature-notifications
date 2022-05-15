<?php

/**
 * Plugin Name: WP Notify
 */

if ( ! defined( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR' ) ) {
	define( 'WP_NOTIFICATION_CENTER_PLUGIN_DIR', dirname( __FILE__ ) );
}

// Require interface/class declarations..
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/interface-wp-notify-exception.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/class-wp-notify-runtime-exception.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/class-wp-notify-invalid-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/exceptions/class-wp-notify-failed-to-add-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/interface-wp-notify-json-unserializable.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/interface-wp-notify-status.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/interface-wp-notify-notification.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notify-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notify-aggregate-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/class-wp-notify-base-notification.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/image/interface-wp-notify-image.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/image/class-wp-notify-base-image.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/senders/interface-wp-notify-sender.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/senders/class-wp-notify-base-sender.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/interface-wp-notify-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/class-wp-notify-recipient-collection.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/class-wp-notify-user-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/class-wp-notify-role-recipient.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/interface-wp-notify-recipient-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/class-wp-notify-base-recipient-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/recipients/class-wp-notify-aggregate-recipient-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/interface-wp-notify-message.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/class-wp-notify-base-message.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/interface-wp-notify-message-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/class-wp-notify-base-message-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/messages/class-wp-notify-aggregate-message-factory.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/persistence/interface-wp-notify-notification-repository.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/persistence/class-wp-notify-abstract-notification-repository.php';
require_once WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/includes/persistence/class-wp-notify-wpdb-notification-repository.php';


/**
 * ! DEVELOPMENT !
 *  to be removed as soon we find a better place to fit this functions
 **/

/**
 * Adds WP Notify icon after the user avatar in the top admin bar in the "secondary" position
 *
 * @param WP_Admin_Bar $wp_admin_bar Toolbar instance.
 */
function wp_admin_bar_wp_notify_item( $wp_admin_bar ) {

	$footer = '<footer>
    <a>
      <a href="settings.html" class="wp-notification-action wp-notification-action-markread button-link">
        <span class="ab-icon dashicons-admin-generic"></span>' . __( 'Configure notification settings' ) . '
      </a>
    </a></footer>';

	$aside = '<aside id="wp-notification-hub">
  <div class="wp-notification-hub-wrapper">
    <h2 class="screen-reader-text">Notifications</h2>
    ' . $footer . '</div></aside>';

	$args = array(
		'id'     => 'wp-notify',
		'title'  => '<span class="ab-icon" aria-hidden="true"></span><span class="ab-label">' . __( 'Notifications' ) . '</span>',
		'parent' => 'top-secondary',
		'meta'   => array(
			'html' => $aside,
		),
	);
	$wp_admin_bar->add_node( $args );

}
add_action( 'admin_bar_menu', 'wp_admin_bar_wp_notify_item', 1 );


/**
 * Register and enqueue a wp notify scripts and stylesheet in WordPress admin.
 */
function wp_notify_enqueue_admin_assets() {

	// Load styles
	wp_register_style( 'wp_notify_css', plugin_dir_url( __FILE__ ) . '/build/wp-notify.css' );
	wp_enqueue_style( 'wp_notify_css' );

	// Load scripts
	wp_register_script( 'wp_notify_js', plugin_dir_url( __FILE__ ) . '/build/wp-notify.js' );
	wp_enqueue_script( 'wp_notify_js' );
}

add_action( 'admin_enqueue_scripts', 'wp_notify_enqueue_admin_assets' );
