<?php

namespace WP\Notifications;

use WP\Notifications\Messages\Message_Factory;
use WP\Notifications\Recipients\Recipient_Factory;
use WP\Notifications\Recipients\Recipient_Collection;
use WP\Notifications\Senders\Sender_Factory;

class Factory {

	const MESSAGE    = 'message';
	const RECIPIENTS = 'recipients';

	/**
	 * Message factory implementation to use.
	 *
	 * @var Message_Factory
	 */
	private $message_factory;

	/**
	 * Recipient factory implementation to use.
	 *
	 * @var Recipient_Factory
	 */
	private $recipient_factory;

	/**
	 * Sender factory implementation to use
	 *
	 * @var Sender_Factory
	 */
	private $sender_factory;

	public function __construct(
		Message_Factory $message_factory,
		Recipient_Factory $recipient_factory,
		Sender_Factory $sender_factory
	) {
		$this->message_factory   = $message_factory;
		$this->recipient_factory = $recipient_factory;
		$this->sender_factory    = $sender_factory;
	}

	/**
	 * Create a notification instance
	 *
	 * @param $args
	 *
	 * @return Base_Notification
	 */
	public function create( $args ) {

		list( $message_args, $recipients_args, $sender_args ) = $this->validate( $args );

		$sender     = $this->sender_factory->create( $sender_args );
		$recipients = new Recipient_Collection();
		$message    = $this->message_factory->create( $message_args );

		foreach ( $recipients_args as $type => $value ) {
			$recipients->add(
				$this->recipient_factory->create( $value, $type )
			);
		}

		return new Base_Notification( $sender, $recipients, $message );
	}

	private function validate( $args ) {
		// TODO: Validate args.
		if ( is_string( $args ) ) {
			$args = json_decode( $args );
		}

		list( $message_args, $recipients_args, $sender_args ) = $args;

		return array(
			$this->get_message_args( $message_args ),
			$this->get_recipients_args( $recipients_args ),
			$this->get_sender_args( $sender_args ),
		);
	}

	private function get_message_args( $args ) {
		// TODO: Parse message args.
		return $args;
	}

	private function get_recipients_args( $args ) {
		// TODO: Parse recipients args.
		return $args;
	}

	public function get_sender_args( $args ) {
		// TODO: Parse sender args.
		return $args;
	}
}
