import { comment, Icon } from '@wordpress/icons';

export const NoticeEmpty = (props) => {
	return (
		<div style={{ padding: '20px', textAlign: 'center' }}>
			<Icon icon={comment} size={props.size} />
			<p>{props.message}</p>
		</div>
	);
};
