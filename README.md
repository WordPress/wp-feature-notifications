# WP Notify

A feature plugin for WordPress, which aims to create a new (better) way to manage and deliver notifications to the relevant audience.

To get started, please take a look at our [project wiki](https://github.com/WordPress/wp-notify/wiki)

## Useful commands

In order to run the following commands, you need to have [Node.js](https://nodejs.org) (including `npm`)

* `npm i`: Installs local development dependencies.
* `npm serve`: Serve bundle at http://localhost:8887
* `npm start`: Watch scripts and styles or Serve @ http://localhost:8887
* `npm start:user-js`: Watch scripts
* `npm start:user-css`: Watch styles
* `npm build`: Build preview

### Structure
* includes/ui/notification-hub/assets - The work folder
  * assets/html - here you will find the notices and the sidebar html
  * assets/script - here for now you will find some scripts that has only the function to test the ui
  * assets/css/notify.scss - the WP-Notify style (this plugin css) 
  * assets/css/wordpress.scss - The WordPress Admin style (will not be included in the future, but at the moment is needed for ui testing purpose)
  * img/ - actually there is the favicon (without that there is an error in console)     
 * includes/ui/notification-hub/dist - Build folder

