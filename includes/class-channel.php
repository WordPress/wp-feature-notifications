<?php
/**
 * Notifications API:Channel class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications;

use JsonSerializable;

/**
 * Class representing a channel.
 *
 * @see register_channel()
 */
class Channel implements JsonSerializable {
	/**
	 * Channel key.
	 *
	 * @var string
	 */
	public string $name;

	/**
	 * Human-readable channel label.
	 *
	 * @var string
	 */
	public string $title = '';

	/**
	 * Channel icon.
	 *
	 * @var string|null
	 */
	public $icon = null;


	/**
	 * Channel description.
	 *
	 * @var string|null
	 */
	public $description = null;

	/**
	 * Constructor.
	 *
	 * Instantiates a Channel object.
	 *
	 * @see register_channel()
	 *
	 * @param string       $channel Channel name including namespace.
	 * @param array|string $args    {
	 *     Array or string of arguments for registering a channel. Supported arguments are
	 *     described below.
	 *
	 *     @type string      $title       Human-readable channel label.
	 *     @type string|null $icon        Optional channel icon.
	 *     @type string|null $description Optional detailed channel description.
	 * }
	 */
	public function __construct( string $channel, $args ) {
		$parsed = wp_parse_args( $args );

		$this->name        = $channel;
		$this->title       = $parsed['title'];
		$this->icon        = array_key_exists( 'icon', $parsed ) ? $parsed['icon'] : null;
		$this->description = array_key_exists( 'description', $parsed ) ? $parsed['description'] : null;
	}

	/**
	 * Specifies data which should be serialized to JSON.
	 *
	 * @return mixed Data which can be serialized by json_encode, which is a
	 *               value of any type other than a resource.
	 */
	public function jsonSerialize(): mixed {
		return array(
			'name'        => $this->name,
			'title'       => $this->title,
			'icon'        => $this->icon,
			'description' => $this->description,
		);
	}
}