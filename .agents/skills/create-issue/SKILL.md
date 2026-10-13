---
name: create-issue
description: "Create or edit a GitHub issue for this repository with the standard template, labels and gh CLI conventions. Use when the user asks to open, write, file, draft or update an issue, bug report or feature request."
---

# Create a GitHub issue

Repository: `justusdeitert/ron-ulrich`.

## Procedure

1. **Investigate** the code involved so the "Current state" section is accurate.
2. **Draft** the issue with the [template](./template.md). Omit a section only if it does not apply; skip "Steps to reproduce" only for pure editor or content tasks.
3. **Pick labels** (see below).
4. **Preview** the full title, body and labels to the user and **wait for confirmation**.
5. **Publish** with `gh`:

```sh
GH_PAGER=cat gh issue create --repo justusdeitert/ron-ulrich \
  --title "..." --label enhancement --body "$(cat <<'EOF'
...
EOF
)"
```

To edit an existing issue: `GH_PAGER=cat gh issue edit <n> --repo justusdeitert/ron-ulrich --body "..."`.

Always prefix `gh` with `GH_PAGER=cat` so the terminal doesn't open a pager.

## Writing rules

- English only. Keep it short, one screen if possible.
- No em dashes or hyphens as sentence separators. Rephrase.
- Code references: backticks for symbols, markdown links for files (e.g. `[vite.php](theme/inc/vite.php#L20)`). Never bare file paths.

## Labels

- `bug`: something is broken for the user.
- `enhancement`: new behaviour or visible improvement.
- `question`: blocked on a client or stakeholder decision. Combine with `enhancement` when applicable.
- `documentation`: README, agent instructions, comments only.

Also available: `duplicate`, `good first issue`, `help wanted`, `invalid`, `wontfix`.
