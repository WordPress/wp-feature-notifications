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
      <a href="' . esc_url( admin_url( 'options-general.php?page=wp-notify' ) ) . '" class="wp-notification-action wp-notification-action-markread button-link">
        <span class="ab-icon dashicons-admin-generic"></span>' . __( 'Configure notification settings' ) . '
      </a>
    </a></footer>';

	$aside = '<aside id="wp-notification-hub">
  <div class="wp-notification-hub-wrapper">
    <h2 class="screen-reader-text">' . __( 'Notifications' ) . '</h2>
    <div id="wp-notify-hub"></div>
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
	wp_enqueue_script('react');
	wp_register_script( 'wp_notify_js', plugin_dir_url( __FILE__ ) . '/build/wp-notify.js' , ['wp-element'], "VERSION", true );
	wp_enqueue_script( 'wp_notify_js' );
}

add_action( 'admin_enqueue_scripts', 'wp_notify_enqueue_admin_assets' );

function wp_notify_admin_notice() {
?>
	<div id="wp-notify-dashboard-notices" class="wrap"></div>
<?php
}
add_action( 'admin_notices', 'wp_notify_admin_notice' );

function wp_notify_admin_notice_demo() {
?>
	<div id="wp-notify-notice-demo" class="wrap"></div>
	<div id="wp-notify-notice-demo-buttons" class="wrap"></div>
<?php
}
add_action( 'admin_notices', 'wp_notify_admin_notice_demo' );


/**
 * Registers the options page.
 *
 * @return void
 */
function wp_notify_add_admin_options_page() {
	add_options_page( 'Notifications', 'Notifications', 'manage_options', 'wp-notify', 'wp_notify_render_admin_options_page' );
}
add_action( 'admin_menu', 'wp_notify_add_admin_options_page' );



/**
 * Renders the options page.
 *
 * @return void
 */
function wp_notify_render_admin_options_page() {
	?>

	<h1><?php _e( 'Notifications settings' ); ?></h1>

	<p>Tailor which kinds of notifications you'd like to receive, and where.</p>

	<p>Want to stay informed on the go? Try out the <a href="#">WordPress mobile app</a>!</p>

	<div class="tablenav top">
		<div class="alignleft actions bulkactions">
			<label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
			<select name="action" id="bulk-action-selector-top">
				<option value="-1">Bulk actions</option>
			</select>
			<input type="submit" id="doaction" class="button action" value="Apply">
		</div>
	</div>
	<h2 class="screen-reader-text">Notifications list</h2>

	<table class="wp-list-table widefat fixed striped table-view-list">

		<thead>
			<tr>
				<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
				<th scope="col" id="source" class="manage-column column-source column-primary sortable desc"><a href="#"><span>Notification Source</span><span class="sorting-indicator"></span></a></th>
				<td id="showin" class="manage-column column-format column-showin"><span>Show in:</span></td>
				<th scope="col" id="admin" class="manage-column column-format column-admin desc"><span>Admin</span></th>
				<th scope="col" id="email" class="manage-column column-format column-email desc"><span>Email</span></th>
				<th scope="col" id="sms" class="manage-column column-format column-sms desc"><span>SMS</span></th>
				<th scope="col" id="app" class="manage-column column-format column-app desc"><span>App</span></th>
			</tr>
		</thead>

		<tbody id="the-list">

			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-123">WordPress</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</th>

				<td class="title column-source has-row-actions column-primary page-source" data-colname="source" colspan="2">
					<strong><a class="row-source" href="#" aria-label="">WordPress</a></strong>
				</td>

				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">Admin</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">Email</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">SMS</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">App</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
			</tr>

			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-123">User activity (published, edited, ect)</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</th>

				<td class="title column-source has-row-actions column-primary page-source" data-colname="source" colspan="2">
					<strong><a class="row-source" href="#" aria-label="">User activity (published, edited, ect)</a></strong>
				</td>

				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">Admin</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">Email</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">SMS</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">App</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
			</tr>

			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-123">Comments</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</th>

				<td class="title column-source has-row-actions column-primary page-source" data-colname="source" colspan="2">
					<strong><a class="row-source" href="#" aria-label="">Comments</a></strong>
				</td>

				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">Admin</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">Email</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">SMS</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">App</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
			</tr>

			<tr>
				<th scope="row" class="check-column">
					<label class="screen-reader-text" for="cb-select-123">Site health</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</th>

				<td class="title column-source has-row-actions column-primary page-source" data-colname="source" colspan="2">
					<strong><a class="row-source" href="#" aria-label="">Site health</a></strong>
				</td>

				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">Admin</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">Email</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">SMS</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
				<td scope="row" class="column-format">
					<label class="screen-reader-text" for="cb-select-123">App</label>
					<input id="cb-select-123" type="checkbox" name="post[]" value="123">
				</td>
			</tr>

		</tbody>

		<tfoot>
			<tr>
				<td id="cb" class="manage-column column-cb check-column"><label class="screen-reader-text" for="cb-select-all-1">Select All</label><input id="cb-select-all-1" type="checkbox"></td>
				<th scope="col" id="source" class="manage-column column-source column-primary sortable desc"><a href="#"><span>Notification Source</span><span class="sorting-indicator"></span></a></th>
				<td id="showin" class="manage-column column-format column-showin"><span>Show in:</span></td>
				<th scope="col" id="admin" class="manage-column column-format column-admin desc"><span>Admin</span></th>
				<th scope="col" id="email" class="manage-column column-format column-email desc"><span>Email</span></th>
				<th scope="col" id="sms" class="manage-column column-format column-sms desc"><span>SMS</span></th>
				<th scope="col" id="app" class="manage-column column-format column-app desc"><span>App</span></th>
			</tr>
		</tfoot>
	</table>

	<?php
}

