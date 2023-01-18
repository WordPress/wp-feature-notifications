import { __ } from '@wordpress/i18n';
import { cog } from '@wordpress/icons';
import { Button } from '@wordpress/components';
import { settingsPageUrl } from '../store/constants';

/**
 * The footer for the notices section drawer.
 *
 * @return {JSX.Element}
 * @function Object() { [native code] }
 */
export const NoticeHubFooter = () => (
	<footer>
		<Button
			href={settingsPageUrl}
			className="wp-notification-action wp-notification-action-markread button-link"
			icon={cog}
			text={__('Configure notification settings')}
		/>
	</footer>
);
