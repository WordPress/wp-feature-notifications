# Translations

See also Database Schema and Database Schema Design.

The title and message of a notification are stored as translated text in the `wp_notifications_messages` table.

They are meant to be translated when a notice is emitted, into the locale of each subscribed user. So a single notice emission may create one message row per locale.

The channel title is not stored. It is read from the `Channel_Registry`, so it is translated when it is displayed, like any other string registered in code.

An earlier idea was to store translation keys, like `title_key` and `message_key`, and let the plugin look up the translated strings when reading. The current schema does not do this.
