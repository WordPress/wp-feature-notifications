# Validate channels and severity when sending notifications

## Summary

At the moment, a notification can be sent:

- **through a channel that was never registered**, because nothing calls `Channel_Registry::is_registered()` on the send path;
- **with any `severity`**, because the column is `VARCHAR(16)` and nothing validates it.

Both should be rejected.

## Why

- Every rule based on channels depends on channels being registered first. That includes muting, intent, and the checks that Plugin Check will run.
- A fixed list of severities is needed before rules such as "promotional is never critical" can be enforced. See [`issue-channel-intent.md`](./issue-channel-intent.md).
- This work is useful even without intent, and it's small enough to land first.

## Work to be done

- [ ] Reject messages sent to unregistered channels, and call `_doing_it_wrong()`. This applies to the PHP send path and to the REST create endpoint.
- [ ] Define the allowed severities as constants in an interface, following `Status_Interface`. Use the values already in the REST schema enum.
- [ ] Reject unknown severities in the message factory and the REST schema.
- [ ] Add PHPUnit tests for both rejections.

## Open questions

- The REST enum allows `alert`, `info`, `warning` and `success`. Which one counts as "critical" for the rule that promotional messages are never critical? Is it `alert`, or both `alert` and `warning`?
- Should `success` stay? In the intent model, success messages are `feedback`, which never goes to the tray. Keeping `success` only makes sense if channels can deliver to the `admin_notice` surface.
