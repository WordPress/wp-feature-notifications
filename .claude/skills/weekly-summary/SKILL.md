---
name: weekly-summary
description: Write a weekly summary of everything that happened in this GitHub repository over the last 7 days (merged/opened/closed PRs, new and closed issues, commits, releases, milestone progress) and save it as a dated markdown file in the drafts/ directory. Run every Friday. Use whenever the user types /weekly-summary or asks for a weekly update, a recap of the week, "what happened this week", a Friday status report, or a summary of recent repo activity, even if they don't say "summary".
---

# Weekly summary

Writes a short, readable recap of the past week's GitHub activity in this repo for the team. It's meant to be skimmed in a minute: what shipped, what's new, what's in progress and what needs attention.

## 1. Choose the date window

By default, cover the 7 days up to and including today (if today is Friday, that's last Friday through today). If the user gives a different start date or range ("since the 1st", "last two weeks"), use that.

## 2. Gather the data

Run the bundled script from the repository root:

```bash
.claude/skills/weekly-summary/scripts/gather.sh [YYYY-MM-DD]
```

It prints labelled JSON sections: merged PRs, opened PRs, PRs closed without merging, opened issues, closed issues, older open items with new activity, commits on the default branch, releases and open milestones. The script uses `gh`, so if it fails because of authentication, tell the user to run `! gh auth login` and stop. Don't guess at the activity.

If something looks significant but you're missing context, such as a large merged PR or an issue with a long new thread, check it with `gh pr view <n>` or `gh issue view <n> --comments`. Don't do this for every item; save it for the few that will be headlines.

## 3. Write the document

Save to `drafts/WEEKLY-SUMMARY-YYYY-MM-DD.md`, using the end date of the window (normally today's Friday). That matches the uppercase names of the other working docs in `drafts/`. If the file already exists, for example because the summary is re-run on the same day, overwrite it with the fresh data.

Use this structure. Leave out any section with nothing in it rather than writing "None", except **Highlights**, which is always present.

```markdown
# Weekly summary: YYYY-MM-DD

_Covers YYYY-MM-DD to YYYY-MM-DD in [owner/repo](https://github.com/owner/repo)._

## Highlights

2–5 bullets in plain language: the things a teammate most needs to know. Lead with outcomes ("Dependabot backlog cleared: 12 PRs merged"), not counts on their own.

## Merged

Human-authored PRs, one line each: [#123](url) title (@author)

**Dependency updates:** a single line or short list grouping bot PRs (e.g. "8 Dependabot bumps merged: node-forge, re-resizable, …").

## New pull requests

Open PRs created this week, noting drafts: [#123](url) title (@author)

## Issues

### Opened
### Closed

## Discussion and activity

Older open issues or PRs with notable new comments or reviews this week. Only list ones with real activity, not label tweaks.

## Milestones

Progress per open milestone: `0.3.0`: 1 closed / 25 open (link). Mention any change this week if you can infer it.

## Needs attention

Optional. Stale review requests, PRs closed without merging, newly opened bugs, anything blocked. Skip it if nothing stands out.
```

### Writing guidance

- **Group bot noise.** Dependabot and other bot PRs can swamp a week. Collapse them into one dependency-updates line per section so human work stays visible. A bot PR is one whose author has `is_bot: true` or a login like `app/dependabot`.
- **Link every number** as `[#123](url)` so readers can click through.
- **Group related items.** If an issue and the PR that fixes it both appeared this week, mention them together (e.g. "[#627](…) fixes [#614](…)") instead of listing each separately in two places. The `closingIssuesReferences` field on PRs tells you which issues a PR closes.
- **Keep it factual.** Summarise what happened, not opinions on code quality. If a title is unclear, a few words of context from the body are fine.
- **Keep it short.** A typical week should fit on one screen. Use bullets, not paragraphs.

## 4. Report back

Tell the user the path of the file you wrote and give the Highlights in a couple of lines. Don't commit the file unless asked.
