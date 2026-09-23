# SESSION_LOOP.md — How Every Accomplish Session Runs

## Purpose

This document defines the fixed procedure for every Accomplish session in the dental content business. It exists so the business runs like a loop, not a series of one-off tasks.

## The loop

### 1. START — Accomplish checks the repo

Before doing any work, Accomplish must read:

1. **README.md** — project overview and current status pointer
2. **PLAN.md** — current phase, daily command queue, strategic review cadence
3. **WORK_LOG.md** — the most recent entry to determine what was already done and the exact next action
4. **docs/BRAND_BRIEF_TEMPLATE.md** — locked-in brand decisions (once finalized)
5. **docs/BUYER_RESEARCH.md** — target audience guardrails

From these files, Accomplish determines:
- Which day of which plan we are on
- What work already exists (so nothing is duplicated)
- What the owner or Jessie has already approved
- Whether revenue is on/off track and a strategic pivot is due

### 2. MIDDLE — Accomplish executes the day's command

Accomplish carries out the work described in `PLAN.md` for that day. During the session, Accomplish may:
- Create/edit files in the repo
- Publish content to the website, Pinterest, Etsy, or TPT
- Analyze traffic, sales, or ad data
- Compile the weekly review packet for Jessie

### 3. END — Accomplish records and pushes

At the end of every session, Accomplish **must** update `WORK_LOG.md` with a dated entry containing:

```markdown
## YYYY-MM-DD — [Day X / Sprint week Y]

### What Accomplish did
- Item 1
- Item 2

### What Jessie reviewed (if applicable)
- Items reviewed
- Approve/fix notes applied

### Key results
- Revenue: $X | Traffic: X | New products: X | New posts: X | New subscribers: X
- Wins: ...
- Friction: ...
- Strategic pivots approved: ...

### Next action
- Owner runs: "[exact next command]"
```

Then Accomplish commits and pushes:

```bash
git add -A
git commit -m "YYYY-MM-DD: [brief summary]"
git push origin main
```

## Owner prompts

You can start any session with one of these commands:

- For the accelerated sprint: `Start accelerated sprint – Day X`
- For the standard plan: `Run Week X, Day Y`
- For strategic review: `Run strategic review: what's working and what should change?`
- For Jessie review day: `Process Jessie's review packet and apply fixes`
- Generic: `Run today's scheduled command from the repo`

If no specific command is given, Accomplish will default to reading `WORK_LOG.md` and running whatever is listed under **Next action**.

## Strategic review cadence

Every two weeks, the **Next action** should be a strategic review. Accomplish will:
1. Pull the last 14 days of work from `WORK_LOG.md`
2. Compare revenue and traffic against the plan
3. Recommend one of: continue, double down, pivot, or cut
4. Update `PLAN.md` only if the owner approves a change

Changes to the plan are made deliberately, not daily, to prevent thrash.

## Logging rules

- One entry per session
- Always include revenue and traffic numbers, even if zero
- Record blockers honestly; don't hide bad days
- Link to specific files or listings when relevant
- Keep the next action explicit enough that a new session can pick it up without asking
