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
	public $name;

	/**
	 * Human-readable channel label.
	 *
	 * @var string
	 */
	public $title = '';

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
	 * @see WP\Notifications\register_channel()
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
	public function __construct( $channel, $args ) {
		$this->name        = $channel;
		$this->title       = $args['title'];
		$this->icon        = array_key_exists( 'icon', $args ) ? $args['icon'] : null;
		$this->description = array_key_exists( 'description', $args ) ? $args['description'] : null;
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
