---
name: new-feature
description: Use this skill when the user asks to add or implement new functionality in fall_guardian_api. Trigger on requests like "add an endpoint", "implement this feature", "create this behavior", "implement a route", "add a route", "build this endpoint", or "implement this in [domain]".
---

# New Feature

Read first:
- `AGENTS.md`
- `.claude/rules/architecture.md`
- `.claude/rules/domain.md`
- `.claude/rules/testing.md`
- `.claude/rules/security.md`

Read as needed:
- `.claude/patterns.md`
- nearby files in the same domain

## Workflow — TDD approach (mandatory)

### Phase 1 — Acceptance criteria

1. If acceptance criteria are missing, ask for them before doing anything else.
2. Turn criteria into a numbered list of verifiable statements. Confirm with the user before proceeding.

### Phase 2 — Tests first (before any implementation)

3. Write the Behat scenario(s) covering the happy path and key failure paths (not found, unauthorized, validation failure).
4. Write the unit tests covering handler/service logic and each execution branch.
5. Run the tests — they must fail for the right reason (no implementation yet, not a setup error).
6. **Stop. Show the failing tests and wait for explicit user confirmation before implementing.**

### Phase 3 — Implementation (after validation)

7. Find the nearest local pattern in the target domain.
8. Reuse the surrounding folder and naming conventions.
9. Keep State Processors thin; Handlers own business flow.
10. Put the Port interface in `src/Domain/{Feature}/Port/`, implementation in `src/Infrastructure/Persistence/`.
11. If API Platform already covers the CRUD behavior cleanly, prefer that over extra domain layers.
12. Implement until all tests pass.
13. Run `make lint && make analyse && make rector` — fix any findings.
14. Run `make test && make test-behat` — all green before reporting done.

## Rules

- Never skip Phase 2. Tests before code, always.
- Never implement in Phase 2. Write tests only — no production classes.
- Prefer readability over premature optimization.
- Do not introduce a conflicting structure inside the touched domain.
- If a criterion cannot be covered by an automated test, flag it explicitly.
