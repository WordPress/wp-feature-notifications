import { Component } from '@wordpress/element';

import wpLogo from '../../images/WordPressLogo.svg';

export default class NoticeImage extends Component {
	render() {
		const classes = 'wp-notification-' + this.props.type;
		const image =
			this.props.image || this.type === 'adminbar' || wpLogo || null;

		return (
			<div
				className={
					this.props.type ? 'wp-notification-image ' + classes : null
				}
			>
				{ this.props.type === 'icon' ? (
					<span className={ 'ab-icon dashicons-' + image }></span>
				) : (
					<img src={ image } alt="" />
				) }
			</div>
		);
	}
}
