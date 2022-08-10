# API

## DashNotices

WP-Notify dashboard notifications
It watches for state updates and renders the Notifications component when it detects a change


*Returns*

- `JSX.Element`: Notifications

*Type Definition*

- *DashNotices* `JSX.Element`

*Properties*

- *notifications* `Object`: - the notification collection

## HubNotice

WP-Notify toolbar in the secondary position of the admin bar
It watches for state updates and renders a <Notifications /> component with the updated state


*Type*

- `Function`store.getState

*Parameters*

- *store* `store`: 

*Returns*

- `JSX.Element`: Notifications

## store

Creating a store for the redux state.


*Returns*

- `Store`: A Redux store that lets you read the state, dispatch actions and subscribe to changes.

*Type Definition*

- *Store* `Object`

*Properties*

- *dispatch* `Function`: - The only way to update the state is to call store.dispatch() and pass in an action object.
- *getState* `Function`: - Returns the current state tree of your application. It is equal to the last value returned by the store's reducer.
