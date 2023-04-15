<?php // phpcs:ignore WordPress.Files.FileName.InvalidClassFileName
/**
 * @package wp-feature-notifications
 *
 * Development demo files
 **/

namespace WP\Notifications;

use WP_Admin_Bar;
use WP_List_Table;

/**
 * Adds WP Notifications icon after the user avatar in the top admin bar in the "secondary" position
 *
 * @param WP_Admin_Bar $wp_admin_bar Toolbar instance.
 */
function admin_bar_item( WP_Admin_Bar $wp_admin_bar ) {
	if ( ! is_admin() ) {
		return;
	}

	$args = array(
		'id'     => 'wp-notifications-hub',
		'title'  => __( 'loading' ),
		'parent' => 'top-secondary',
		'meta'   => array(
			'tabindex' => 0,
		),
	);
	$wp_admin_bar->add_node( $args );
}
add_action( 'admin_bar_menu', '\WP\Notifications\admin_bar_item', 1 );

/**
 * Adds WP Notifications area at the top of the dashboard
 */
function admin_notice() {
	if ( is_admin() && get_current_screen()->id === 'dashboard' ) {
		echo '<div id="wp-notifications-dashboard" class="wrap"></div>';
	}
}
add_action( 'admin_notices', '\WP\Notifications\admin_notice' );

/**
 * Register and enqueue a wp-notifications scripts and stylesheet in WordPress admin.
 */
function enqueue_admin_assets() {
	/* Load styles */
	wp_register_style( 'wp_notifications', WP_FEATURE_NOTIFICATION_PLUGIN_DIR_URL . '/build/wp-notifications.css', array(), WP_FEATURE_NOTIFICATION_PLUGIN_VERSION );
	wp_enqueue_style( 'wp_notifications' );

	/* Load scripts */
	$asset = include WP_FEATURE_NOTIFICATION_PLUGIN_DIR . '/build/wp-notifications.asset.php';
	wp_register_script( 'wp_notifications', WP_FEATURE_NOTIFICATION_PLUGIN_DIR_URL . '/build/wp-notifications.js', $asset['dependencies'], WP_FEATURE_NOTIFICATION_PLUGIN_VERSION, true );
	wp_enqueue_script( 'wp_notifications' );

	wp_localize_script(
		'wp_notifications',
		'wp_notifications_data',
		array(
			'settingsPage' => esc_url( admin_url( 'options-general.php?page=notifications' ) ),
		)
	);
}

add_action( 'admin_enqueue_scripts', '\WP\Notifications\enqueue_admin_assets', 0 );


/**
 * Registers the options page.
 *
 * @return void
 */
function add_admin_options_page() {
	add_options_page( 'Notifications', 'Notifications', 'manage_options', 'notifications', '\WP\Notifications\render_admin_options_page' );
}
add_action( 'admin_menu', '\WP\Notifications\add_admin_options_page' );



/**
 * Renders the options page.
 */
function render_admin_options_page() {    ?>

	<h1><?php _e( 'Notifications settings' ); ?></h1>

	<p>Tailor which kinds of notifications you'd like to receive, and where.</p>

	<p>Want to stay informed on the go? Try out the <a href="#">WordPress mobile app</a>!</p>

	<h2 class="screen-reader-text">Notifications list</h2>
	<?php
	/**
	 * List_table that shows the settings
	 */
	class List_Table extends WP_List_Table {
		/**
		 * Prepare the items for the table to process
		 */
		public function prepare_items() {
			$columns  = $this->get_columns();
			$hidden   = $this->get_hidden_columns();
			$sortable = $this->get_sortable_columns();

			$data = $this->table_data();

			$per_page     = 10;
			$current_page = $this->get_pagenum();
			$total_items  = count( $data );

			$this->set_pagination_args(
				array(
					'total_items' => $total_items,
					'per_page'    => $per_page,
				)
			);

			$data = array_slice( $data, ( ( $current_page - 1 ) * $per_page ), $per_page );

			$this->_column_headers = array( $columns, $hidden, $sortable );
			$this->items           = $data;
		}

		/**
		 * Override the parent columns method. Defines the columns to use in your listing table
		 *
		 * @return Array
		 */
		public function get_columns(): array {
			return array(
				'cb'     => '<input type="checkbox" />',
				'source' => __( 'Notification Source' ),
				'show'   => __( 'Show in:' ),
				'admin'  => __( 'Admin' ),
				'email'  => __( 'Email' ),
				'sms'    => __( 'Sms' ),
				'app'    => __( 'App' ),
			);
		}

		public function get_hidden_columns(): array {
			return array();
		}

		public function get_sortable_columns(): array {
			return array( 'source' => array( 'source', false ) );
		}

		private function table_data(): array {
			$data = array();

			/**
			 * Notification sources
			 *
			 * Allows to filter the notification sources that will be shown in the table.
			 *
			 * @since 0.0.2
			 *
			 * @param array $indexes Array of available notification emitters
			 */
			$indexes = apply_filters(
				'wp_feature_notifications_settings_indexes',
				array(
					'WordPress',
					'User activity (published, edited, ect)',
					'Comments',
					'Site health',
				)
			);

			$item_count = count( $indexes );

			for ( $i = 0; $i < $item_count; $i++ ) {
				$label      = $indexes[ $i ];
				$data[ $i ] = array(
					'cb'     => $i,
					'source' => $label,
					'show'   => '',
					'admin'  => '<input type="checkbox" id="cb-admin-' . $i . '" />',
					'email'  => '<input type="checkbox" id="cb-email-' . $i . '" />',
					'sms'    => '<input type="checkbox" id="cb-sms-' . $i . '" />',
					'app'    => '<input type="checkbox" id="cb-app-' . $i . '" />',
				);
			}
			return $data;
		}

		public function column_default( $item, $column_name ) {
			switch ( $column_name ) {
				case 'cb':
				case 'source':
				case 'show':
				case 'admin':
				case 'email':
				case 'sms':
				case 'app':
					return $item[ $column_name ];

				default:
					return $item;
			}
		}
	}

	/**
	 * The settings table
	 *
	 * @return void
	 */
	function init_table() {
		/** @var $list_table $list_table */
		$list_table = new list_table();
		$list_table->prepare_items();
		$list_table->display();
		printf( '<input type="submit" id="doaction" class="button action primary" value="%s">', __( 'Apply' ) );
	}
	init_table();
}



/**
 * Registers our dashboard widget.
 */
function dashboard_widget() {
	add_meta_box( 'wp_notifications', __( 'WP Notifications Feature' ), '\WP\Notifications\render_dashboard_widget', 'dashboard', 'side', 'high' );
}
add_action( 'wp_dashboard_setup', '\WP\Notifications\dashboard_widget' );


/**
 * Renders our dashboard widget.
 */
function render_dashboard_widget() {
	?>
	<div id="wp-notification-metabox">
		<form id="wp-notification-metabox-form" class="initial-form hide-if-no-js">
			<p><?php esc_attr_e( 'Use the form below to beta test the new notifications feature.' ); ?></p>
			<div class="input-text-wrap" id="title-wrap">
				<label for="wp-notification-metabox-form-title"><?php esc_attr_e( 'Title' ); ?></label>
				<input type="text" id="wp-notification-metabox-form-title" autocomplete="off">
			</div>

			<div class="textarea-wrap" id="description-wrap">
				<label for="wp-notification-metabox-form-message"><?php esc_attr_e( 'Content' ); ?></label>
				<textarea id="wp-notification-metabox-form-message" placeholder="<?php esc_attr_e( 'What\'s on your mind?' ); ?>" class="mceEditor" rows="3" cols="15" autocomplete="off"></textarea>
			</div>

			<p class="submit">
				<input type="submit" name="save" id="save-wp-notifications" class="button button-primary" value="<?php esc_attr_e( 'Test Notification' ); ?>">
				<input type="button" id="clear-all-wp-notifications" class="button" value="<?php esc_attr_e( 'Clear All Notifications' ); ?>">
				<br class="clear">
			</p>
		</form>
	</div>
	<?php
}
