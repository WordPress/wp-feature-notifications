# Translations

See also Database Schema

To allow for internationalisation, the title and message text for a single notification is purposefully not stored in the database.

Instead, a plugin or theme should store a key, which is linked to a translatable message in the plugin or theme's translation files.

For example, given the following list of translatable strings in a plugin 

```
$notifications = array( 
    'plugin_slug_new_podcast_title' => __('Some title', 'plugin-slug'), 
    'plugin_slug_message' => __('Some message', 'plugin-slug'), 
);
```

When adding a notification to the wp_notifications table, the values will be stored as follows

```
title_key = 'plugin_slug_new_podcast_title'
message_key = 'plugin_slug_message'
```

The plugin will then be responsible for looking up these strings, and returning the translated version.