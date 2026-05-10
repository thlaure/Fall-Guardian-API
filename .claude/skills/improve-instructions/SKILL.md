---
name: improve-instructions
description: Use this skill when the user asks to improve fall_guardian_api agent instructions or keep AGENTS.md and .claude files aligned with the evolving codebase. Also trigger on phrases like "update the rules", "update the skill", "change AGENTS.md", "update the instructions", "the instructions are wrong", or "add this to the guidelines".
---

# Improve Instructions

Read first:
- `AGENTS.md`
- `CLAUDE.md`
- `.claude/rules/architecture.md`
- `.claude/rules/domain.md`
- `.claude/rules/testing.md`
- `.claude/rules/security.md`

Read as needed:
- `.claude/patterns.md`
- `.claude/skills/*/SKILL.md`
- `Makefile`
- `composer.json`

Workflow:
1. Inspect the current repository and guidance.
2. Identify durable drift between the codebase and instruction files.
3. Keep only reusable improvements.
4. Propose exact updates and rationale.
5. Ask for confirmation before applying any edit.

Rules:
- Never edit instruction files silently.
- Keep `AGENTS.md` canonical and `CLAUDE.md` as a pointer only.
