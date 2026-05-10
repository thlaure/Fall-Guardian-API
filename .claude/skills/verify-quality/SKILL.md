---
name: verify-quality
description: Use this skill when the user asks to run the full quality gate (lint, rector, analyse, tests, behat) or validate that changes are ready to ship. Trigger on phrases like "run the checks", "validate my changes", "is this ready", "run quality gates", or "make sure everything passes". Prefer debug-quality when the user is reporting a specific failure.
---

# Verify Quality

Read first:
- `AGENTS.md`
- `.claude/rules/testing.md`
- `.claude/rules/security.md`

Read as needed:
- `Makefile`
- `composer.json`

Workflow:
1. Discover the canonical repository commands from the Makefile.
2. Run the narrowest relevant checks first, then broader required ones.
3. Prefer fixing PHPStan issues in code, types, or PHPDoc instead of changing `phpstan.neon`.
4. Report failures with the command, affected area, and smallest likely fix direction.

Typical order:
1. `make lint`
2. `make analyse`
3. `make rector`
4. `make test` (all PHPUnit suites)
5. `make test-integration` when persistence behavior changed
6. `make test-behat` when API acceptance behavior changed or feature files were touched

Rules:
- If a command is unavailable, say so instead of inventing a substitute.
- Prefer fixing PHPStan issues in code, types, or PHPDoc instead of changing `phpstan.neon`.
- Treat edits to `phpstan.neon` as exceptional and ask first.
