export const hydrate = (payload) => {
	return {
		type: 'HYDRATE',
		payload,
	};
};

export const addNotice = (payload) => {
	return {
		type: 'ADD',
		payload,
	};
};

export const removeNotice = (key) => {
	return {
		type: 'DELETE',
		key
	};
};

export const updateNotice = (payload) => {
	return {
		type: 'UPDATE',
		payload,
	};
};

export const fetchAPI = (path = '') => {
	return {
		type: 'FETCH',
		path,
	};
};
