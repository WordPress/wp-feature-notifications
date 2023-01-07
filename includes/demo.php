<?php
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
	if ( ! is_admin() ) {
		return;
	}

	$footer = sprintf(
		'<footer>
		<a href="%s" class="wp-notification-action wp-notification-action-markread button-link">
			<span class="ab-icon dashicons-admin-generic"></span>%s
		</a>
	</footer>',
		esc_url( admin_url( 'options-general.php?page=wp-notify' ) ),
		__( 'Configure notification settings' )
	);

	$aside = sprintf(
		'<aside id="wp-notification-hub">
		<div class="wp-notification-hub-wrapper">
			<h2 class="screen-reader-text">%s</h2>
			<div id="wp-notify-adminbar"></div>%s
		</div>
	</aside>',
		__( 'Notifications' ),
		$footer
	);

	$args = array(
		'id'     => 'wp-notify',
		'title'  => sprintf( "<span class='ab-icon' aria-hidden='true'></span><span class='ab-label'>%s</span>", __( 'Notifications' ) ),
		'parent' => 'top-secondary',
		'meta'   => array(
			'html'     => $aside,
			'tabindex' => 0,
		),
	);
	$wp_admin_bar->add_node( $args );
}
add_action( 'admin_bar_menu', 'wp_admin_bar_wp_notify_item', 1 );

/**
 * Adds WP Notify area at the top of the dashboard
 */
function wp_notify_admin_notice() {
	 // WP-Notify wrapper
	echo '<div id="wp-notify-dashboard" class="wrap"></div>';
}
add_action( 'admin_notices', 'wp_notify_admin_notice' );

/**
 * Register and enqueue a wp notify scripts and stylesheet in WordPress admin.
 */
function wp_notify_enqueue_admin_assets() {
	 // Load styles
	wp_register_style( 'wp_notify_css', WP_NOTIFICATION_CENTER_PLUGIN_DIR_URL . '/build/wp-notify.css', array(), WP_NOTIFICATION_CENTER_PLUGIN_VERSION );
	wp_enqueue_style( 'wp_notify_css' );

	// Load scripts
	$asset = include WP_NOTIFICATION_CENTER_PLUGIN_DIR . '/build/wp-notify.asset.php';
	wp_register_script( 'wp_notify_js', WP_NOTIFICATION_CENTER_PLUGIN_DIR_URL . '/build/wp-notify.js', $asset['dependencies'], WP_NOTIFICATION_CENTER_PLUGIN_VERSION, true );
	wp_enqueue_script( 'wp_notify_js' );
	wp_localize_script(
		'wp_notify_js',
		'wp_notify_data',
		array(
			'pluginUrl' => WP_NOTIFICATION_CENTER_PLUGIN_DIR_URL,
		)
	);
}

add_action( 'admin_enqueue_scripts', 'wp_notify_enqueue_admin_assets', 0 );


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
 */
function wp_notify_render_admin_options_page() {    ?>

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
			usort( $data, array( &$this, 'sort_data' ) );

			$perPage     = 10;
			$currentPage = $this->get_pagenum();
			$totalItems  = count( $data );

			$this->set_pagination_args(
				array(
					'total_items' => $totalItems,
					'per_page'    => $perPage,
				)
			);

			$data = array_slice( $data, ( ( $currentPage - 1 ) * $perPage ), $perPage );

			$this->_column_headers = array( $columns, $hidden, $sortable );
			$this->items           = $data;
		}

		/**
		 * Override the parent columns method. Defines the columns to use in your listing table
		 *
		 * @return Array
		 */
		public function get_columns(): array {
			$columns = array(
				'cb'     => '<input type="checkbox" />',
				'source' => __( 'Notification Source' ),
				'show'   => __( 'Show in:' ),
				'admin'  => __( 'Admin' ),
				'email'  => __( 'Email' ),
				'sms'    => __( 'Sms' ),
				'app'    => __( 'App' ),
			);

			return $columns;
		}

		public function get_hidden_columns(): array {
			return array();
		}

		public function get_sortable_columns(): array {
			return array( 'source' => array( 'source', false ) );
		}

		private function table_data(): array {
			$data = array();

			$indexes = array(
				'WordPress',
				'User activity (published, edited, ect)',
				'Comments',
				'Site health',
			);

			for ( $i = 0; $i < count( $indexes ); $i++ ) {
				$label      = $indexes[ $i ];
				$data[ $i ] = array(
					'cb'     => $i,
					'source' => $label,
					'show'   => '',
					'admin'  => '<input type="checkbox" id="cb-admin-' . sanitize_title( $label ) . '" />',
					'email'  => '<input type="checkbox" id="cb-email-' . sanitize_title( $label ) . '" />',
					'sms'    => '<input type="checkbox" id="cb-sms-' . sanitize_title( $label ) . '" />',
					'app'    => '<input type="checkbox" id="cb-app-' . sanitize_title( $label ) . '" />',
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
					return print_r( $item, true );
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
	}
	init_table();
}



/**
 * Registers our dashboard widget.
 */
function wp_notify_dashboard_widget() {
	add_meta_box( 'wp_notify', __( 'WP Notify' ), 'wp_notify_render_dashboard_widget', 'dashboard', 'side', 'high' );
}
add_action( 'wp_dashboard_setup', 'wp_notify_dashboard_widget' );


/**
 * Renders our dashboard widget.
 */
function wp_notify_render_dashboard_widget() {
	?>
	<div id="wp-notification-metabox">
		<form id="wp-notification-metabox-form" class="initial-form hide-if-no-js">
			<p><?php esc_attr_e( 'Use the form below to beta test the new notifications feature.' ); ?></p>
			<div class="input-text-wrap" id="title-wrap">
				<label for="title"><?php esc_attr_e( 'Title' ); ?></label>
				<input type="text" name="post_title" id="wp-notification-metabox-form-title" autocomplete="off">
			</div>

			<div class="textarea-wrap" id="description-wrap">
				<label for="content"><?php esc_attr_e( 'Content' ); ?></label>
				<textarea name="content" id="wp-notification-metabox-form-message" placeholder="<?php esc_attr_e( 'What\'s on your mind?' ); ?>" class="mceEditor" rows="3" cols="15" autocomplete="off"></textarea>
			</div>

			<p class="submit">
				<input type="submit" name="save" id="save-wp-notify" class="button button-primary" value="<?php esc_attr_e( 'Test Notification' ); ?>">
				<input type="button" id="clear-all-wp-notify" class="button" value="<?php esc_attr_e( 'Clear All Notifications' ); ?>">
				<br class="clear">
			</p>
		</form>
	</div>
	<?php
}
