import { Notice } from './Notice';

/**
 * Returns a list of notices
 * each notice is a component that has a key, an id, an image, an additional class name, and an onDismiss function
 *
 * @param {Object} prop         -
 * @param {Array}  prop.notices - An array of objects that contain the notice data.
 *
 * @return {Array} An array of Notice components.
 */
export const NoticesLoop = ({ notices }) =>
	notices.map((notify, index) => <Notice {...notify} key={index} />);
