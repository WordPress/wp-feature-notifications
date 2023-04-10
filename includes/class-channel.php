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
 * @see wp_feature_notifications_register_channel()
 */
class Channel implements JsonSerializable {
	/**
	 * Channel key.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * Human-readable channel label.
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Channel icon.
	 *
	 * @var string|null
	 */
	protected $icon = null;


	/**
	 * Channel description.
	 *
	 * @var string|null
	 */
	protected $description = null;

	/**
	 * Constructor.
	 *
	 * Instantiates a Channel object.
	 *
	 * @see wp_feature_notifications_register_channel()
	 *
	 * @param string      $channel     Channel name including namespace.
	 * @param string      $title       Human-readable channel label.
	 * @param string|null $icon        Channel icon.
	 * @param string|null $description A detailed channel description.
	 */
	public function __construct( $channel, $title, $icon = null, $description = null ) {
		$this->name        = $channel;
		$this->title       = $title;
		$this->icon        = $icon;
		$this->description = $description;
	}

	/**
   * Get the name of the channel.
   *
   * @return string Name of the channel.
   */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get the title of the channel.
	 *
	 * @return string Title of the channel.
	 */
	public function get_title() {
		return $this->title;
	}

	/**
	 * Get the icon of the channel.
	 *
	 * @return string|null Icon of the channel.
	 */
	public function get_icon() {
		return $this->icon;
	}

	/**
	 * Get the icon of the description.
	 *
	 * @return string|null Description of the channel.
	 */
	public function get_description() {
		return $this->description;
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
