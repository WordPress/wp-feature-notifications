<?php
/**
 * Notifications API:Message class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications;

use JsonSerializable;

/**
 * Class representing a message.
 */
class Message implements JsonSerializable {

	/**
	 * Message title.
	 *
	 * @var string
	 */
	public $title;

	/**
	 * Message content.
	 *
	 * @var string
	 */
	public $content = '';

	/**
	 * Message icon.
	 *
	 * @var string|null
	 */
	public $icon = null;

	/**
	 * Message context.
	 *
	 * @var string
	 */
	public $context = null;

	/**
	 * Message content.
	 *
	 * @var string
	 */
	public $severity = null;

	/**
	 * Constructor.
	 *
	 * Instantiates a Message object.
	 *
	 * @param string      $title    Message title.
	 * @param string      $content  Message content.
	 * @param string|null $icon     Message icon.
	 * @param string|null $context  Message context.
	 * @param string|null $severity Message severity.
	 */
	public function __construct( $title, $content, $icon = null, $context = null, $severity = null ) {
		$this->title    = $title;
		$this->content  = $content;
		$this->icon     = $icon;
		$this->context  = $context;
		$this->severity = $severity;
	}

	/**
	 * Specifies data which should be serialized to JSON.
	 *
	 * @return mixed Data which can be serialized by json_encode, which is a
	 *               value of any type other than a resource.
	 */
	public function jsonSerialize(): mixed {
		return array(
			'title'    => $this->title,
			'content'  => $this->content,
			'icon'     => $this->icon,
			'context'  => $this->context,
			'severity' => $this->severity,
		);
	}
}
