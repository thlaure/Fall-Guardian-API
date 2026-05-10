---
name: prepare-commit
description: Use this skill when the user says "prepare commit", "prepare a commit", "commit", asks to stage files, write a Conventional Commit message, or prepare PR notes for fall_guardian_api. Trigger proactively — do not handle commit preparation manually.
---

# Prepare Commit

Read first:
- `AGENTS.md`
- `Makefile`

Read as needed:
- `git status`
- `git diff`
- `git log`

Workflow:
1. Inspect the current branch and working tree.
2. Stage only the intended files with `git add`.
3. Run the relevant quality checks, general review, and security review before proposing the commit.
4. If the current branch is protected, create a dedicated branch before committing when the user confirms the branch name.
5. Build a Conventional Commit title and optional body.
6. Prepare PR-ready notes and a checkbox-style verification list.
7. If the user explicitly asks to commit, ask for confirmation before running `git commit`.
8. If the user explicitly asks to push, ask for confirmation before running `git push`.

Rules:
- Never commit or push silently.
- Treat `git commit` and `git push` as confirmation-required actions in the current conversation.
- Do not assume all changed files belong to the intended commit.
- If pre-commit checks find blockers, report them before proposing the final commit.
