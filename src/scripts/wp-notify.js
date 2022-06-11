/**
 * WP Notify - main
 */
import { Component, createElement, render } from '@wordpress/element';

// Images TODO: remove import
import wpLogo from '../images/WordPressLogo.svg';

// Redux stuff
import { configureStore } from '@reduxjs/toolkit';
import notifyReducer, {
	addNotice,
	removeNotice,
	clearNotices,
	fetchApi,
} from './reducer';
import Notifications from './components';

/**
 * Toggle notification hub
 */
const wpNotifyHub = document.getElementById("wp-admin-bar-wp-notify");

const disableNotifyDrawer = () => {
  wpNotifyHub.classList.remove("active");
  document.body.removeEventListener("click", disableNotifyDrawer);
};

// handle click on wp-admin bar bell icon that show the WP-Notify sidebar
wpNotifyHub.addEventListener("click", function (e) {
  e.stopPropagation();
  if (!wpNotifyHub.classList.contains("active")) {
    this.classList.add("active");
    document.body.addEventListener("click", disableNotifyDrawer);
  }
});

// The dashboard notifications context
const NotifyContext = createContext();

/**
 * Enable the main dash notifications if available
 */
const notifyHub = document.getElementById( 'wp-notify-hub' );
const notifyDash = document.getElementById( 'wp-notify-dashboard-notices' );

/**
 * Redux config
 */
export const store = configureStore( {
	reducer: {
		notifications: notifyReducer,
	},
} );

window.wp.notify = [];
wp.notify.fetch = ( url ) => store.dispatch( fetchApi( url ) );
wp.notify.add = ( props ) => store.dispatch( addNotice( props ) );
wp.notify.remove = ( key, location = 'adminbar' ) =>
	store.dispatch( removeNotice( { key, location } ) );
wp.notify.clear = ( location = 'adminbar' ) =>
	store.dispatch( clearNotices( location ) );

/**
 * WP-Notify dashboard notifications
 */
class DashNotices extends Component {
	state = {
		notifications: { ...store.getState().notifications },
	};

	constructor( props ) {
		super( props );
		// watch for state updates
		store.subscribe( () => {
			this.setState( {
				notifications: { ...store.getState().notifications },
			} );
		} );
	}

	render() {
		return <Notifications location={ 'dashboard' } />;
	}
}
render( createElement( DashNotices ), notifyDash );

/**
 * WP-Notify toolbar in the secondary position of the admin bar
 */
class HubNotice extends Component {
	state = {
		notifications: { ...store.getState().notifications },
	};

	constructor( props ) {
		super( props );
		// watch for state updates
		store.subscribe( () => {
			this.setState( {
				notifications: { ...store.getState().notifications },
			} );
		} );
	}

	render() {
		return (
			<>
				<section>
					<header>
						<h2>2 unread notifications</h2>
						<button
							id="clear-all-wp-notify-hub"
							className="wp-notification-action wp-notification-action-markread button-link"
						>
							<span className="ab-icon dashicons-saved"></span>{ ' ' }
							Mark all as read
						</button>
					</header>

					<Notifications location={ 'adminbar' } />

					<div
						className="wp-notification plugin unread"
						role="status"
					>
						<div className="wp-notification-wrap">
							<h4 className="wp-notification-title">WordPress</h4>
							<p className="wp-notification-message">
								WordPress was successfully updated to version
								5.9.
							</p>
							<a href="#" className="wp-notification-action">
								Read what's new in 5.9
							</a>
							<p className="wp-notification-source">
								<span className="name">WordPress</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">1d ago</span>
							</p>
						</div>
						<div className="wp-notification-image">
							<img src={ wpLogo } />
						</div>
					</div>

					<div
						className="wp-notification plugin unread"
						role="status"
					>
						<div className="wp-notification-wrap">
							<p className="wp-notification-message">
								WordPress was successfully updated to version
								5.9.
							</p>
							<p className="wp-notification-source">
								<span className="name">WordPress</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">1d ago</span>
							</p>
						</div>
						<div className="wp-notification-image default">
							<img src={ wpLogo } />
						</div>
					</div>
				</section>

				<section>
					<header>
						<h2>Older notifications</h2>
					</header>

					<div className="wp-notification plugin" role="status">
						<div className="wp-notification-wrap">
							<h4 className="wp-notification-title">WordPress</h4>
							<p className="wp-notification-message">
								WordPress was successfully updated to version
								5.9.
							</p>
							<a href="#" className="wp-notification-action">
								Read what's new in 5.9
							</a>
							<p className="wp-notification-source">
								<span className="name">WordPress</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">1d ago</span>
							</p>
						</div>
						<div className="wp-notification-image default">
							<img src={ wpLogo } />
						</div>
					</div>

					<div className="wp-notification plugin" role="status">
						<div className="wp-notification-wrap">
							<h4 className="wp-notification-title">WordPress</h4>
							<p className="wp-notification-message">
								WordPress was successfully updated to version
								5.9.
							</p>
							<a href="#" className="wp-notification-action">
								Read what's new in 5.9
							</a>
							<p className="wp-notification-source">
								<span className="name">WordPress</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">1d ago</span>
							</p>
						</div>
						<div className="wp-notification-image default">
							<img src={ wpLogo } />
						</div>
					</div>

					<div className="wp-notification plugin" role="status">
						<div className="wp-notification-wrap">
							<p className="wp-notification-message">
								There is a new version of Contact Form 7
								available.
							</p>
							<a href="#" className="wp-notification-action">
								Update now
							</a>
							<p className="wp-notification-source">
								<span className="name">WordPress</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">1d ago</span>
							</p>
						</div>
						<div className="wp-notification-image default">
							<img src="https://ps.w.org/contact-form-7/assets/icon-256x256.png" />
						</div>
					</div>

					<div className="wp-notification plugin" role="status">
						<div className="wp-notification-wrap">
							<h4 className="wp-notification-title">Akismet</h4>
							<a href="#" className="wp-notification-action">
								Your API key is no longer valid.
							</a>
							<p className="wp-notification-source">
								<span className="name">WordPress</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">5d ago</span>
							</p>
						</div>
						<div className="wp-notification-image">
							<img
								src="https://ps.w.org/akismet/assets/icon-256x256.png"
								className="wp-notification-image"
							/>
						</div>
					</div>

					<div className="wp-notification notify" role="status">
						<div className="wp-notification-wrap">
							<h4 className="wp-notification-title">Default</h4>
							<p className="wp-notification-source">
								<span className="name">WordPress</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">2d ago</span>
							</p>
						</div>
						<div className="wp-notification-image">
							<span className="ab-icon dashicons-paperclip"></span>
						</div>
					</div>

					<div
						className="wp-notification notify wp-notification-error"
						role="status"
					>
						<div className="wp-notification-wrap">
							<h4 className="wp-notification-title">
								Site Health status
							</h4>
							<p className="wp-notification-message">
								Your site has critical issues that should be
								addressed
							</p>
							<p className="wp-notification-source">
								<span className="name">WordPress</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">1d ago</span>
							</p>
						</div>
						<div className="wp-notification-image">
							<span className="ab-icon dashicons-sos"></span>
						</div>
					</div>

					<div
						className="wp-notification notify wp-notification-warning"
						role="status"
					>
						<div className="wp-notification-wrap">
							<p className="wp-notification-message">
								Some plugins needs to be updated
							</p>
							<a href="#" className="wp-notification-action">
								Update now
							</a>
							<p className="wp-notification-source">
								<span className="name">WordPress</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">1d ago</span>
							</p>
						</div>
						<div className="wp-notification-image">
							<span className="ab-icon dashicons-admin-plugins"></span>
						</div>
					</div>

					<div
						className="wp-notification notify wp-notification-success"
						role="status"
					>
						<div className="wp-notification-wrap">
							<h4 className="wp-notification-title">
								Word Camp Europe
							</h4>
							<p className="wp-notification-message">
								Word Camp was successfully updated to version
								2022
							</p>
							<p className="wp-notification-source">
								<span className="name">WordPress</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">1d ago</span>
							</p>
						</div>
						<div className="wp-notification-image">
							<span className="ab-icon dashicons-megaphone"></span>
						</div>
					</div>

					<div className="wp-notification user" role="status">
						<div className="wp-notification-wrap">
							<p className="wp-notification-message">
								WordPress User has left a message on Lorem
								Ipsum.
							</p>
							<p className="wp-notification-source">
								<span className="name">Comment</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">5d ago</span>
							</p>
						</div>
						<div className="wp-notification-image">
							<img src="https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&f=y" />
						</div>
					</div>

					<div className="wp-notification user" role="status">
						<div className="wp-notification-wrap">
							<p className="wp-notification-message">
								Another user left a message on Hello World.
							</p>
							<p className="wp-notification-source">
								<span className="name">Comment</span>{ ' ' }
								{ '\u2022' + ' ' }
								<span className="date">6d ago</span>
							</p>
						</div>
						<div className="wp-notification-image default">
							<img src="https://secure.gravatar.com/avatar/61ee2579b8905e62b4b4045bdc92c11a" />
						</div>
					</div>
				</section>
			</>
		);
	}
}
render( createElement( HubNotice ), notifyHub );
