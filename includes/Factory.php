<?php

class WPNotify_Factory {

	const MESSAGE    = 'message';
	const RECIPIENTS = 'recipients';

	/**
	 * Message factory implementation to use.
	 *
	 * @var WPNotify_MessageFactory
	 */
	private $message_factory;

	/**
	 * Recipient factory implementation to use.
	 *
	 * @var WPNotify_RecipientFactory
	 */
	private $recipient_factory;

	public function __construct(
		WPNotify_MessageFactory $message_factory,
		WPNotify_RecipientFactory $recipient_factory
	) {

		$this->message_factory   = $message_factory;
		$this->recipient_factory = $recipient_factory;
	}

	public function create( $args ) {
		list( $message_args, $recipients_args ) = $this->validate( $args );

		$message = $this->message_factory->create( $message_args );

		$recipients = new WPNotify_RecipientCollection();
		foreach ( $recipients_args as $type => $value ) {
			$recipients->add(
				$this->recipient_factory->create( $value, $type )
			);
		}

		return new WPNotify_BaseNotification( new BaseSender(), $message, $recipients );
	}

	private function validate( $args ) {
		// TODO: Validate args.
		if ( is_string( $args ) ) {
			$args = json_decode( $args );
		}

		list( $message_args, $recipients_args ) = $args;

		return array(
			$this->get_message_args( $message_args ),
			$this->get_recipients_args( $recipients_args ),
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
}
