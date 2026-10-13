---
name: release-theme
description: "Release a new version of the WordPress theme: version bump, production build, release commit, tag, push and GitHub release notes. Use only when the user explicitly asks to release, publish, tag or bump the version."
---

# Release the theme

Only run this when the user explicitly asks for a release. Every step that changes git history or GitHub needs the user's confirmation.

## 1. Determine version

- **Patch** (1.0.0 -> 1.0.1): bug fixes, small enhancements, dependency updates, UI refinements to existing features.
- **Minor** (1.0.1 -> 1.1.0): significant new features or capabilities.
- Find the last tag (`git describe --tags --abbrev=0`; if there is none, use the full history), review `git --no-pager log <tag>..HEAD --oneline`, propose a version and **wait for confirmation**.

## 2. Bump version

- Update `Version` in `theme/style.css`.
- Update `version` in `theme/package.json`.
- Run `docker compose exec node yarn install` to update `theme/yarn.lock`.

## 3. Build

- Run `make build` and make sure it succeeds.

## 4. Commit and tag

- Stage only `theme/style.css`, `theme/package.json`, `theme/yarn.lock`.
- Commit message: `chore: release v<version>`.
- Create a **lightweight** tag (not annotated): `git tag v<version>`.

## 5. Push (after confirmation)

- `git push origin main --tags`

## 6. GitHub release

- `GH_PAGER=cat gh release create v<version> --title "v<version>" --notes "<notes>"`
- Summarize the commits since the last tag, one concise line each, grouped into these sections (omit empty ones):

```markdown
### Added
- Feature description

### Fixed
- Fix description

### Changed
- Change description
```
