/* global pagenow */
import demoImage from '../images/i.svg';

// delay util function
import { delay } from './utils';

import { store } from './wp-notify';
import { clearNotices } from './reducer';

window.addEventListener( 'load', () => {
	const wpNotificationMetabox = document.getElementById( 'wp-notification-metabox-form' );

	if ( ! wpNotificationMetabox ) {
		return;
	}
	// Click handler to add a new notification
	document
		.getElementById( 'wp-notification-metabox-form' )
		.addEventListener( 'submit', function( e ) {
			e.preventDefault();
			const title = document.getElementById(
				'wp-notification-metabox-form-title'
			).value;
			const message = document.getElementById(
				'wp-notification-metabox-form-message'
			).value;

			wp.notify.add( {
				title,
				message,
				location: 'dashboard',
			} );
		} );

	// flush all notices
	document
		.getElementById( 'clear-all-wp-notify' )
		.addEventListener( 'click', () => store.dispatch( clearNotices( 'dashboard' ) ) );

	document
		.getElementById( 'clear-all-wp-notify-hub' )
		.addEventListener( 'click', () => store.dispatch( clearNotices( 'adminbar' ) ) );
} );

/**
 * add some demo notifications to the dashboard
 */
if (
	pagenow &&
  ( pagenow === 'settings_page_wp-notify' || pagenow === 'dashboard' )
) {
	( async () => await wp.notify.add( {
		location: 'dashboard',
		image: demoImage,
		title: 'Try this new Notification feature',
		source: '#WP-Notify',
		message:
        'We have just added a <b>wonderful feature!</b> You might want to give it a try so click on the bell icon on the right side of the adminbar.',
		acceptMessage: 'Try this new feature',
		acceptLink: 'https://github.com/WordPress/wp-notify',
		dismissible: false,
	} ) )()
		.then( () => delay( 600 ) )
		.then( () => {
			wp.notify.add( {
				location: 'dashboard',
				image: 'https://source.unsplash.com/random/400×400/?notify',
				title: 'Message variant #1',
				message: 'This is an example of on-page message variant #1. It has a title, a message, an image, an action button with a URL, is dismissable.',
				source: '#Test',
				dismissible: true,
				acceptMessage: 'TEST',
				acceptLink: 'https://github.com/WordPress/wp-notify',
			} );
			wp.notify.add( {
				title: 'Message variant #1 (copy)',
				message: 'A short message',
				source: '#Test',
				acceptMessage: 'TEST',
				acceptLink: 'https://github.com/WordPress/wp-notify',
			} );
		} )
		.then( () => delay( 1200 ) )
		.then( () =>
			wp.notify.add( {
				location: 'adminbar',
				title: 'Message variant #2',
				source: '#WP-Notify',
				date: new Date().toLocaleDateString(),
				message:
				// eslint-disable-next-line max-len
          'This is an example of on-page message variant #2. It has a title, a message, a custom date, an action button with a URL, is dismissable, but has no images.',
				acceptMessage: 'OK',
				acceptLink: 'https://github.com/WordPress/wp-notify',
				dismissible: true,
			} ) )
		.then( () => delay( 2400 ) )
		.then( () =>
			wp.notify.add( {
				location: 'dashboard',
				image:
            'https://gifimage.net/wp-content/uploads/2018/10/animation-notification-gif-2.gif',
				title: 'Message variant #3',
				message:
            'if you\'re wondering where notice #2 is try looking at the adminbar at the top right near the bell icon 😉.',
				acceptLink: 'https://github.com/WordPress/wp-notify',
				dismissible: true,
			} )
		);
}
