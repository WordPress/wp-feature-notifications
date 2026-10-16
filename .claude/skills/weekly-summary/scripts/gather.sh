#!/usr/bin/env bash
# Gather a week of GitHub activity for the current repository as labelled JSON blocks.
# Usage: gather.sh [SINCE_DATE]   (YYYY-MM-DD, defaults to 7 days ago)
set -euo pipefail

SINCE="${1:-$(date -v-7d +%F 2>/dev/null || date -d '7 days ago' +%F)}"
REPO="$(gh repo view --json nameWithOwner --jq .nameWithOwner)"
DEFAULT_BRANCH="$(gh repo view --json defaultBranchRef --jq .defaultBranchRef.name)"

section() { echo; echo "=== $1 ==="; }

echo "repo: $REPO"
echo "default_branch: $DEFAULT_BRANCH"
echo "since: $SINCE"
echo "until: $(date +%F)"

section "PRS_MERGED"
gh pr list --state merged --search "merged:>=$SINCE" --limit 200 \
	--json number,title,author,mergedAt,labels,milestone,url,closingIssuesReferences

section "PRS_OPENED"
gh pr list --state all --search "created:>=$SINCE" --limit 200 \
	--json number,title,author,state,isDraft,createdAt,labels,milestone,url,closingIssuesReferences

section "PRS_CLOSED_UNMERGED"
gh pr list --state closed --search "closed:>=$SINCE is:unmerged" --limit 200 \
	--json number,title,author,closedAt,url

section "ISSUES_OPENED"
gh issue list --state all --search "created:>=$SINCE" --limit 200 \
	--json number,title,author,state,createdAt,labels,milestone,url

section "ISSUES_CLOSED"
gh issue list --state closed --search "closed:>=$SINCE" --limit 200 \
	--json number,title,author,closedAt,stateReason,labels,milestone,url

section "ISSUES_AND_PRS_UPDATED_BUT_NOT_OPENED_OR_CLOSED"
# Older items with fresh discussion or review activity.
gh search issues --repo "$REPO" --updated ">=$SINCE" --created "<$SINCE" --state open --limit 100 \
	--json number,title,isPullRequest,commentsCount,updatedAt,url

section "COMMITS_ON_DEFAULT_BRANCH"
gh api "repos/$REPO/commits?sha=$DEFAULT_BRANCH&since=${SINCE}T00:00:00Z&per_page=100" \
	--jq '[.[] | {sha: .sha[0:7], message: (.commit.message | split("\n")[0]), author: (.author.login // .commit.author.name), date: .commit.author.date}]'

section "RELEASES"
gh api "repos/$REPO/releases?per_page=10" \
	--jq "[.[] | select(.published_at >= \"$SINCE\") | {tag: .tag_name, name, prerelease, published_at, url: .html_url}]"

section "MILESTONES"
gh api "repos/$REPO/milestones?state=open" \
	--jq '[.[] | {title, open: .open_issues, closed: .closed_issues, due_on, url: .html_url}]'
