---
name: scan-project
description: Use this skill when the user asks to inspect, understand, or map the fall_guardian_api repository before making changes. Trigger on requests like "look at this repo", "understand this domain", or "where should this change go?".
---

# Scan Project

Read first:
- `AGENTS.md`
- `.claude/rules/architecture.md`
- `.claude/rules/testing.md`

Read as needed:
- `Makefile`
- `composer.json`
- `.claude/patterns.md`

Workflow:
1. Inspect `src/Domain/`, `src/Infrastructure/`, `src/Entity/`, `src/Enum/`, `tests/`, and `features/`.
2. Identify the local pattern in the target domain.
3. Summarize likely files to touch, constraints, and risks.

Rules:
- Prefer local repository patterns over generic Symfony defaults.
- Port interfaces live in domain; Doctrine implementations live in Infrastructure.
- Do not invent a cleaner architecture than what is present in the target area.
