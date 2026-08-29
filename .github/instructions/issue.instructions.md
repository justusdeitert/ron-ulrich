---
description: "Use when creating or editing a GitHub issue via `gh`. Standard template, labels, and CLI conventions."
---

# Issue Format

Use this template for every new issue. Omit a section only if it does not apply.

```markdown
## Problem

One or two sentences describing the user-visible symptom.

## Current state

Where the relevant code lives and why it behaves this way. Link files using workspace-relative paths with line numbers (e.g. `[vite.php](theme/inc/vite.php#L20)`).

## Steps to reproduce

1. ...
2. ...
3. ...

**Expected:** ...
**Actual:** ...

## Proposed approach

Concrete change. Bullet sub-points for implementation details.

**Alternative:** Cheaper or different approach, with trade-off.

## Acceptance criteria

- [ ] Verifiable, observable check
- [ ] Verified on Safari desktop + iOS (when UI-facing)
```

# Rules

- English only.
- Keep it short. One screen if possible.
- No em dashes or hyphens as sentence separators. Rephrase.
- Skip "Steps to reproduce" only for pure editor / content tasks.
- Code references: backticks for symbols, markdown links for files. Never bare file paths.

# Labels

Available: `bug`, `documentation`, `duplicate`, `enhancement`, `good first issue`, `help wanted`, `invalid`, `question`, `wontfix`.

- `bug`: something is broken for the user.
- `enhancement`: new behaviour or visible improvement.
- `question`: blocked on a client / stakeholder decision. Combine with `enhancement` when applicable.
- `documentation`: README, instructions, comments only.

# CLI

- Always prefix `gh` with `GH_PAGER=cat` in this workspace (terminal opens an alternate buffer otherwise).
- Repo: `jss-de/ron-ulrich`.
- Create: `GH_PAGER=cat gh issue create --title "..." --body "$(cat <<'EOF'\n...\nEOF\n)" --label enhancement`
- Edit body: `GH_PAGER=cat gh issue edit <n> --body $'...'`
- Preview the full body to the user before pushing.
