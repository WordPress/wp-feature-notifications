# API

## default

Undocumented declaration.

## notifyDash

Renders the DashNotices component


## notifyHub

Renders the HubNotice component


## store

Creating a store for the redux state.


*Returns*

- `Store`: A Redux store that lets you read the state, dispatch actions and subscribe to changes.

*Type Definition*

- *Store* `Object`

*Properties*

- *dispatch* `Function`: - The only way to update the state is to call store.dispatch() and pass in an action object.
- *getState* `Function`: - Returns the current state tree of your application. It is equal to the last value returned by the store's reducer.
