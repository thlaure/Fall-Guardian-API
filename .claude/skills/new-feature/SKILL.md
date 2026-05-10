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

Workflow:
1. If acceptance criteria are missing, ask for them before implementing.
2. Find the nearest local pattern in the target domain.
3. Reuse the surrounding folder and naming conventions.
4. Keep State Processors thin and Handlers responsible for business flow.
5. Put the Port interface in `src/Domain/{Feature}/Port/` and the implementation in `src/Infrastructure/Persistence/`.
6. If API Platform already supports the needed CRUD behavior cleanly, prefer that over extra domain layers.
7. Add the right tests in the same session.
8. Run the relevant verification commands before reporting completion.

Rules:
- Prefer readability over premature optimization.
- Keep the result easy for a human reviewer to understand quickly.
- Do not introduce a conflicting structure inside the touched domain.
