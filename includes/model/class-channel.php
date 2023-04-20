<?php
/**
 * Notifications API:Channel class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Model;

use JsonSerializable;

/**
 * Class representing a notification channel.
 *
 * @see register_channel()
 */
class Channel implements JsonSerializable {

	/**
	 * Name, including namespace, of the channel.
	 *
	 * @var string
	 */
	protected string $name;

	/**
	 * Human-readable label of the channel.
	 *
	 * @var string
	 */
	protected string $title;

	// Optional properties

	/**
	 * Display context of the channel.
	 */
	protected ?string $context;

	/**
	 * Detailed description of the channel.
	 */
	protected ?string $description;

	/**
	 * Icon of the channel.
	 */
	protected ?string $icon;

	/**
	 * Constructor.
	 *
	 * Instantiates a Channel object.
	 *
	 * @see register_channel()
	 *
	 * @param string  $name        Name, including namespace, of the channel.
	 * @param string  $title       Human-readable label of the channel.
	 * @param ?string $context     Optional default display context of the channel.
	 * @param ?string $description Optional detailed description of the channel.
	 * @param ?string $icon        Optional icon of the channel.
	 *
	 */
	public function __construct( $name, $title, $context = null, $description = null, $icon = null ) {
		$this->name  = $name;
		$this->title = $title;

		// Optional properties

		$this->context     = $context;
		$this->icon        = $icon;
		$this->description = $description;
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
			'context'     => $this->context,
			'icon'        => $this->icon,
			'description' => $this->description,
		);
	}

	/**
	 * Get the namespaced name.
	 *
	 * @return string The namespaced name of the channel.
	 */
	public function get_name(): string {
		return $this->name;
	}

	/**
	 * Get the human-readable label.
	 *
	 * @return string The title of the channel.
	 */
	public function get_title(): string {
		return $this->title;
	}

	/**
	 * Get the default display context.
	 *
	 * @return ?string The context of the channel.
	 */
	public function get_context(): ?string {
		return $this->context;
	}

	/**
	 * Get the detailed description.
	 *
	 * @return ?string The description of the channel.
	 */
	public function get_description(): ?string {
		return $this->description;
	}

	/**
	 * Get the icon.
	 *
	 * @return ?string The icon of the channel.
	 */
	public function get_icon(): ?string {
		return $this->icon;
	}
}
