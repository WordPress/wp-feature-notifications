<?php
/**
 * Notifications API:Messages factory class
 *
 * @package wordpress/wp-feature-notifications
 */

namespace WP\Notifications\Factory;

use DateTime;
use WP\Notifications\Framework;
use WP\Notifications\Model;

/**
 * Class representing a messages factory.
 *
 * @implements Framework\Factory<Message>
 */
class Message extends Framework\Factory {

	/**
	 * Instantiates a Message object.
	 *
	 * @param array|string   $args    {
	 *     Array or string of arguments for creating a message. Supported arguments
	 *     are described below.
	 *
	 *     @type ?string   $message        Text content of the message.
	 *     @type ?string   $accept_label   Optional label of the accept action.
	 *     @type ?string   $accept_link    Optional url of the accept action.
	 *     @type ?string   $accept_message Optional label of the accept action.
	 *     @type ?string   $channel_title  Optional human-readable title of the channel
	 *                                     the message was emitted from.
	 *     @type ?DateTime $created_at     Optional datetime at which a message was created.
	 *                                     Default `'null'`
	 *     @type ?string   $dismiss_label  Optional label of the dismiss action.
	 *     @type ?DateTime $expires_at     Optional datetime at which a message expires.
	 *                                     Default `'null'`
	 *     @type ?string   $icon           Optional icon of the message. Default `null`
	 *     @type ?int      $id             Optional database ID of the message. Default `null`
	 *     @type ?bool     $is_dismissible Optional boolean of whether the notice can
	 *                                     be dismissed. Default `true`
	 *     @type ?string   $severity       Optional severity of the message. Default `null`
	 *     @type string    $title          Optional human-readable message label. Default `''`
	 * }
	 *
	 * @return Model\Message A newly created instance of Message or false.
	 */
	public function make( $args = array() ): Model\Message {
		$parsed = wp_parse_args( $args );

		// Required properties

		$message = array_key_exists( 'message', $parsed ) ? $parsed['message'] : null;

		// Optional properties

		$accept_label   = array_key_exists( 'accept_label', $parsed ) ? $parsed['accept_label'] : null;
		$accept_link    = array_key_exists( 'accept_link', $parsed ) ? $parsed['accept_link'] : null;
		$channel_title  = array_key_exists( 'channel_title', $parsed ) ? $parsed['channel_title'] : null;
		$created_at     = array_key_exists( 'created_at', $parsed ) ? $parsed['created_at'] : null;
		$dismiss_label  = array_key_exists( 'dismiss_label', $parsed ) ? $parsed['dismiss_label'] : null;
		$expires_at     = array_key_exists( 'expires_at', $parsed ) ? $parsed['expires_at'] : null;
		$icon           = array_key_exists( 'icon', $parsed ) ? $parsed['icon'] : null;
		$id             = array_key_exists( 'id', $parsed ) ? $parsed['id'] : null;
		$is_dismissible = array_key_exists( 'is_dismissible', $parsed ) ? $parsed['is_dismissible'] : true;
		$severity       = array_key_exists( 'severity', $parsed ) ? $parsed['severity'] : null;
		$title          = array_key_exists( 'title', $parsed ) ? $parsed['title'] : '';

		return new Model\Message(
			$message,
			$accept_label,
			$accept_link,
			$channel_title,
			$created_at,
			$dismiss_label,
			$expires_at,
			$icon,
			$id,
			$is_dismissible,
			$severity,
			$title,
		);
	}
}
