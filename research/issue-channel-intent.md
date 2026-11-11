# Add "intent" to channel registration

## Summary

When a plugin registers a notification channel, it should also say **why** that channel sends notifications. We call this the channel's **intent**.

There are three intents, and core controls the list:

| Intent | Meaning | Example |
|---|---|---|
| `operational` | Something happened that the user may need to know about or act on | An update is available, a new order arrived |
| `setup` | Helping the user finish configuring something | "Connect your account to finish setup" |
| `promotional` | Marketing, tips, upsells, or offers | "Upgrade to Pro and save 20%" |

## Why

This idea, "declared intent", came up in the [design discussion on Make/Core](https://make.wordpress.org/core/2026/10/29/exploring-a-design-for-feature-notifications/). Channels already group notifications by topic, such as `core/updates`. However, a channel's name doesn't tell WordPress what kind of notification it sends. `acme/orders` and `acme/deals` look the same to the system.

Adding an intent to each channel means:

- **Users can mute by intent.** For example, they can turn off all promotional notifications from a plugin with one switch.
- **Tools can check it.** Tools like Plugin Check can read a plugin's channels and see what each one is for.
- **WordPress can enforce rules.** For example, a promotional notification can never be marked critical.

## Example

```php
register_channel(
	'my-plugin/orders',
	array(
		'title'  => __( 'Orders', 'my-plugin' ),
		'intent' => 'operational',
	)
);
```

## Work to be done

**Channel registration**
- [ ] Add an `intent` property to the `Channel` model.
- [ ] Make `intent` required in `register_channel()`. Reject missing or unknown values with `_doing_it_wrong()`.
- [ ] Set an intent on every core channel in `includes/channels.php`.

**Sending notifications**
- [ ] Only allow a notification to be sent through a registered channel.
- [ ] Have each notification take its intent from its channel.
- [ ] Limit `severity` to a fixed list of values.
- [ ] Prevent `promotional` notifications from using a critical severity.

**Storage and API**
- [ ] Store the intent with each notification, so it stays available after the plugin that sent it is removed.
- [ ] Return `intent` in the channel and notification REST API responses.

**Preferences**
- [ ] Let users mute notifications by intent, in addition to muting by channel.

**Tests and docs**
- [ ] Add PHPUnit tests for validation, the severity rule, and the REST responses.
- [ ] Update the developer docs and the database schema docs.

## Open questions

- Should the list of intents be filterable, or fixed in core? A fixed list is easier for tools to check and harder to abuse.
- Should `promotional` notifications be off by default?
