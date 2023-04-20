import {create} from '@storybook/theming';
import Logo from '../stories/assets/logo.svg';

export default create({
	base: 'light',
	brandTitle: 'WP Feature Notifications storybook',
	brandUrl: 'https://github.com/WordPress/wp-feature-notifications',
	brandImage: Logo,
	brandTarget: '_self',
});
