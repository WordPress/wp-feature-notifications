import { Component } from '@wordpress/element';
import NoticesLoop from './NoticesLoop';
import { store } from '../wp-notify';

/**
 * WP-Notify toolbar in the secondary position of the admin bar
 * It watches for state updates and renders a <Notifications /> component with the updated state
 *
 * @module HubNotice
 * @type {store} store
 *
 * @return {JSX.Element} Notifications
 */
export default class Notices extends Component {
	state = {
		notifications: { ...store.getState().notifications },
	};

	constructor(props) {
		super(props);
		this.splitBy = this.props.splitBy;
		this.location = this.props.location;
		// watch for state updates
		store.subscribe(() => {
			this.setState({
				notifications: { ...store.getState().notifications },
			});
		});
	}

	render() {
		return <NoticesLoop location={this.location} splitBy={this.splitBy} />;
	}
}
