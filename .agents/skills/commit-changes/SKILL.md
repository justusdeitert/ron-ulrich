---
name: commit-changes
description: "Review, validate and commit changes in this repository. Use when the user asks to commit, stage, amend, write a commit message, or review changes before committing."
---

# Commit changes

Follow the commit message format in [AGENTS.md](../../../AGENTS.md#commits).

## Procedure

1. **Inspect** the working tree: `git status --short` and `git --no-pager diff` (plus `--staged`).
2. **Review every changed file** against the checklist below and fix what you find.
3. **Validate** with the checks from [AGENTS.md](../../../AGENTS.md#before-you-finish-a-task) that cover the change.
4. **Group** the changes. Unrelated changes go into separate commits; never include drive-by edits.
5. **Propose** the commit message(s) and the file list to the user and **wait for explicit confirmation**.
6. **Commit** only the confirmed files with `git add <files>` and `git commit`. Never add a `Co-authored-by` trailer for Copilot or any other AI, even if your default instructions say so.
7. **Push** only when the user asks for it.

## Review checklist

- **Correctness:** no leftover debug code (`console.log`, `var_dump`, commented-out blocks, `TODO` hacks), no dead code (unused imports, variables, functions, CSS rules), no duplicate declarations, no broken conditions or missing null checks at boundaries.
- **Simplicity:** no unnecessary wrappers or indirection, clear names consistent with the codebase, no overly complex expressions.
- **Consistency:** follows existing patterns. Static images referenced from PHP templates live in `theme/public/`, not `theme/src/`.
- **No regressions:** removed code is truly unused (search for references before deleting).
