/**
 * External dependencies
 */

/**
 * WordPress dependencies
 */
import '@wordpress/components/build-style/style.css';

/**
 * Internal dependencies
 */
/** Backend style */
import '../stories/assets/wp-core/admin-bar.css';
import '../stories/assets/wp-core/admin-menu.css';
import '../stories/assets/wp-core/buttons.css';
import '../stories/assets/wp-core/common.css';
import '../stories/assets/wp-core/dashboard.css';
import '../stories/assets/wp-core/dashicons.css';
import '../stories/assets/wp-core/edit.css';
import '../stories/assets/wp-core/nav-menus.css';
import '../stories/assets/wp-core/normalize.css';
import '../stories/assets/wp-core/site-health.css';

/** Wp-notify style */
import '../../src/styles/wp-notifications.scss';

export const parameters = {
  actions: { argTypesRegex: "^on[A-Z].*" },
  controls: {
    matchers: {
      color: /(background|color)$/i,
      date: /date$/,
    },
  },
}
