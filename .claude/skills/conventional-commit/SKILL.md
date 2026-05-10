---
name: conventional-commit
description: Use this skill whenever the user asks to commit changes, create a commit message, or says things like "commit my changes", "what should my commit message be", "write the commit". Also trigger when the user mentions creating a branch or asks for the git workflow.
version: 1.0.0
disable-model-invocation: true
allowed-tools: Bash
---

# Conventional Commits for fall_guardian_api

## Format

```
{type}({scope}): {description}
```

## Types

| Type | When to use |
|---|---|
| `feat` | New endpoint, handler, domain feature, push delivery path |
| `fix` | Bug fix in existing behaviour |
| `refactor` | Code restructuring with no behaviour change |
| `test` | Adding or fixing tests (unit, integration, Behat) |
| `docs` | Documentation only |
| `chore` | Build, config, dependency, Docker changes |
| `perf` | Performance improvement |

## Scope

The scope is the domain or feature name in **snake_case**, matching the folder name under `src/Domain/`.

- `alert`, `caregiver`, `device`, `contact`, `push`, `sms`, `healthcheck`
- If the change touches multiple domains: use the primary one, or omit the scope

## Description rules

- Imperative mood: "add", "fix", "extract" — not "added", "fixing", "extracts"
- Lowercase
- No period at the end
- Short (under 72 characters total for the first line)

## Examples

```
feat(alert): add cancel fall alert endpoint
fix(caregiver): handle missing push token on invite
test(device): add unit tests for device registration handler
refactor(alert): extract push escalation to dedicated service
chore: update docker compose healthcheck for messenger
docs: document alert flow in AGENTS.md
```

## Branch naming

```
{type}/{short-description}
```

Examples:
```
feat/cancel-fall-alert
fix/missing-push-token
refactor/alert-escalation-service
```

## Git workflow

```bash
# Create branch (never commit directly to master or main)
git checkout -b feat/{description}

# Stage only the files you changed
git add src/Domain/...  tests/...  features/...
```

## Rules

- **Never commit silently** — ask for explicit confirmation in the current conversation.
- **Never push silently** — ask for explicit confirmation in the current conversation.
- One logical change per commit — don't bundle unrelated fixes.
- Always run or report the relevant quality checks before proposing the final commit.
- If `make lint` modifies files, stage those changes and include them.
