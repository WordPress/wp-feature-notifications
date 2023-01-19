export const hydrate = (payload) => {
	return {
		type: 'HYDRATE',
		payload,
	};
};

export const clear = (context) => {
	return {
		type: 'CLEAR',
		context,
	};
};

export const addNotice = (payload) => {
	return {
		type: 'ADD',
		payload,
	};
};

export const removeNotice = (id) => {
	return {
		type: 'DELETE',
		id,
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
