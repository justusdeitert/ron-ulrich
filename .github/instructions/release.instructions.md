---
description: "Use when creating a release, bumping the version, tagging, or publishing to GitHub. Covers version bump, commit, tag, push, and GitHub release steps."
---

# Release Flow

When creating a new release, follow these steps exactly:

## 1. Determine version

- Use **patch** bump (e.g. 1.0.0 -> 1.0.1) for bug fixes, small enhancements, dependency updates, and UI refinements to existing features.
- Use **minor** bump (e.g. 1.0.1 -> 1.1.0) for significant new features or capabilities.
- Always propose the recommended version and wait for explicit user confirmation before bumping.

## 2. Bump version

- Update `Version` in `theme/style.css`.
- Update `version` in `theme/package.json`.
- Run `yarn install` inside the node container to update `yarn.lock`.

## 3. Build production assets

- Run `make build` to generate production assets in `theme/assets/`.

## 4. Create release commit

- Stage the changed files: `theme/style.css`, `theme/package.json`, `theme/yarn.lock`.
- Commit message: `chore: release v<version>`

## 5. Tag

- Create a **lightweight** tag (not annotated): `git tag v<version>`

## 6. Push

- Push commit and tag: `git push origin main --tags`

## 7. Create GitHub release

- Use `gh release create v<version> --title "v<version>" --notes "<notes>"`
- Release notes format (use these sections as applicable, omit empty sections):

```markdown
### Added
- Feature description

### Fixed
- Fix description

### Changed
- Change description
```

- Summarize commits since the last release tag. Group by category. Keep descriptions concise (one line each).
