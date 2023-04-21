<?php
/**
 * Notifications API:Message class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Model;

use DateTime;
use JsonSerializable;
use WP\Notifications\Helper;

/**
 * Class representing a message.
 */
class Message implements JsonSerializable {

	/**
	 * The accepted keys of the message metadata.
	 */
	static protected $meta_keys = array(
		'accept_label',
		'accept_link',
		'channel_title',
		'dismiss_label',
		'expires_at',
		'icon',
		'is_dismissible',
		'severity',
	);

	// Required properties

	/**
	 * Text content of the message.
	 */
	protected ?string $message;

	// Optional properties

	/**
	 * Label of the accept action.
	 */
	protected ?string $accept_label;

	/**
	 * URL of the accept action.
	 */
	protected ?string $accept_link;

	/**
	 * Human-readable title of the channel of which the message was emitted.
	 */
	protected ?string $channel_title;

	/**
	 * Datetime at which a message was created.
	 */
	protected ?DateTime $created_at;

	/**
	 * Label of the dismiss action.
	 */
	protected ?string $dismiss_label;

	/**
	 * Datetime at which a message expires.
	 */
	protected ?DateTime $expires_at;

	/**
	 * Icon of the message.
	 */
	protected ?string $icon;

	/**
	 * Database primary key value.
	 */
	protected ?int $id;

	/**
	 * Whether the notice can be dismissed.
	 */
	protected ?bool $is_dismissible;

	/**
	 * Severity of the message.
	 */
	protected ?string $severity;

	/**
	 * Human-readable message label.
	 */
	protected ?string $title;

	/**
	 * Constructor.
	 *
	 * Instantiates a Message object.
	 *
	 * @param ?string    $message        Text content of the message.
	 * @param ?string    $accept_label   Optional label of the action.
	 * @param ?string    $accept_link    Optional URL of the action.
	 * @param ?string    $channel_title  Optional human-readable title of the channel
	 *                                   the message was emitted from.
	 * @param ?DateTime  $created_at     Optional datetime at which the message was
	 *                                   created. Default `'null'`
	 * @param ?string    $dismiss_label  Optional label of the dismiss action.
	 * @param ?DateTime  $expires_at     Optional datetime at which a message expires.
	 *                                   Default `'null'`
	 * @param ?string    $icon           Optional icon of the message. Default `null`
	 * @param ?int       $id             Optional database ID of the message. Default `null`
	 * @param ?bool      $is_dismissible Optional boolean of whether the notice can be
	 *                                   dismissed. Default `true`
	 * @param ?string    $severity       Optional severity of the message. Default `null`
	 * @param ?string    $title          Optional human-readable label of the message.
	 */
	public function __construct(
		$message = null,
		$accept_label = null,
		$accept_link = null,
		$channel_title = null,
		$created_at = null,
		$dismiss_label = null,
		$expires_at = null,
		$icon = null,
		$id = null,
		$is_dismissible = true,
		$severity = null,
		$title = null
	) {
		// Required properties

		$this->message = $message;

		// Optional properties

		$this->accept_label   = $accept_label;
		$this->accept_link    = $accept_link;
		$this->channel_title  = $channel_title;
		$this->created_at     = $created_at;
		$this->dismiss_label  = $dismiss_label;
		$this->expires_at     = $expires_at;
		$this->icon           = $icon;
		$this->id             = $id;
		$this->is_dismissible = $is_dismissible;
		$this->severity       = $severity;
		$this->title          = $title;
	}

	/**
	 * Specifies data which should be serialized to JSON.
	 *
	 * @return mixed Data which can be serialized by json_encode, which is a
	 *               value of any type other than a resource.
	 */
	public function jsonSerialize() {
		return array_merge(
			$this->collect_meta(),
			array(
				'created_at' => Helper\Serde::maybe_serialize_json_date( $this->created_at ),
				'expires_at' => Helper\Serde::maybe_serialize_json_date( $this->expires_at ),
				'id'         => $this->id,
				'message'    => $this->message,
				'title'      => $this->title,
			)
		);
	}

	/**
	 * Returns the JSON representation of the message metadata, or `false` if encoding fails.
	 *
	 * @return string|false
	 */
	public function encode_meta() {
		return json_encode( $this->collect_meta() );
	}

	/**
	 * Get the title.
	 *
	 * @return ?string The title of the message.
	 */
	public function get_title(): ?string {
		return $this->title;
	}

	/**
	 * Get the content.
	 *
	 * @return ?string The content of the message.
	 */
	public function get_message(): ?string {
		return $this->message;
	}

	// Optional property getters

	/**
	 * Get the accept action label.
	 *
	 * @return ?string The accept action label of message.
	 */
	public function get_accept_label(): ?string {
		return $this->accept_label;
	}

	/**
	 * Get the accept action link.
	 *
	 * @return ?string The accept action link of the message.
	 */
	public function get_accept_link(): ?string {
		return $this->accept_link;
	}

	/**
	 * Get the created at datetime.
	 *
	 * @return ?DateTime The datetime at which the message was created.
	 */
	public function get_created_at(): ?DateTime {
		return $this->created_at;
	}

	/**
	 * Get whether the message is dismissible.
	 *
	 * @return ?bool The is dismissible property of the message.
	 */
	public function get_is_dismissible(): ?bool {
		return $this->is_dismissible;
	}

	/**
	 * Get the dismiss label.
	 *
	 * @return ?string The dismiss label of the message.
	 */
	public function get_dismiss_label(): ?string {
		return $this->dismiss_label;
	}

	/**
	 * Get the expires at datetime.
	 *
	 * @return ?DateTime The expires at datetime of the message.
	 */
	public function get_expires_at(): ?DateTime {
		return $this->expires_at;
	}

	/**
	 * Get the icon.
	 *
	 * @return ?string The icon of the message.
	 */
	public function get_icon(): ?string {
		return $this->icon;
	}

	/**
	 * Get the database ID.
	 *
	 * @return ?int The database ID of the message.
	 */
	public function get_id(): ?int {
		return $this->id;
	}

	/**
	 * Get the severity.
	 *
	 * @return ?string The severity of the message.
	 */
	public function get_severity(): ?string {
		return $this->severity;
	}

	/**
	 * Check whether the content of the message is valid.
	 */
	protected function validate_message() {
		if ( ! is_string( $this->message ) ) {
			return false;
		}

		$length = mb_strlen( $this->message, '8bit' );

		if ( $length > 255 ) {
			return false;
		}

		return true;
	}

	/**
	 * Collect the message metadata values which are non null.
	 *
	 * @return mixed The metadata of the message.
	 */
	protected function collect_meta() {
		$metadata = array();

		foreach ( self::$meta_keys as $key ) {
			if ( null !== $this->{ $key } ) {
				$metadata[ $key ] = $this->{ $key };
			}
		}

		return $metadata;
	}
}
