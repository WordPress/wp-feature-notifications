export class Poller {
	/**
	 * On pages like the post editor or post list, the heartbeat is fired right at the beginning
	 * of the page load. We already fetched the notifications on page load, so this would result
	 * in a duplicate request. To prevent this, we skip the first heartbeat tick for the first X seconds.
	 */
	SKIP_FIRST_INTERVAL = 5000;
	public skipFirstBeat = true;

	constructor( shouldSkipFirstBeat = true ) {
		this.skipFirstBeat = shouldSkipFirstBeat;
	}

	public pollUpdates() {
		if ( this.skipFirstBeat ) {
			return;
		}
		window.wp.notifications.fetchUpdates( true );
	}

	public unsetSkipFirstBeat() {
		setTimeout( () => {
			this.skipFirstBeat = false;
		}, this.SKIP_FIRST_INTERVAL );
	}

	public start() {
		if ( this.skipFirstBeat ) {
			this.unsetSkipFirstBeat();
		}

		wp.hooks.addAction(
			'heartbeat.tick',
			'wp-notifications/pollUpdates',
			this.pollUpdates.bind( this )
		);
	}

	public stop() {
		wp.hooks.removeAction(
			'heartbeat.tick',
			'wp-notifications/pollUpdates',
			this.pollUpdates.bind( this )
		);
	}
}
