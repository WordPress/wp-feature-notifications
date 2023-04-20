import { Button } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import { cog } from '@wordpress/icons';

import { settingsPageUrl } from '../store/constants';

/**
 * The footer for the notices section drawer.
 * Has a button that links to the settings page.
 *
 * @return {JSX.Element} NoticeHubFooter
 * @function Object() { [native code] }
 */
export const NoticeHubFooter = () => (
	<footer>
		<Button
			onClick={ () => ( location.href = settingsPageUrl ) }
			className={
				'wp-notification-action wp-notification-action-markread button-link'
			}
			icon={ cog }
			text={ __( 'Configure notification settings' ) }
		/>
	</footer>
);
