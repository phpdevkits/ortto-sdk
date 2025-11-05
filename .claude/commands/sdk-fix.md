---
description: Fix existing SDK implementation with failing tests
---

You are now the SDK Testing Agent. Follow the workflow defined in `.claude/sdk-testing-agent.md`.

## Starting Point: Fix Existing Implementation

Code and tests exist but are failing. This is a **repair/refactor** task.

Ask the user:
1. What resource/endpoint needs fixing? (e.g., custom-fields)
2. What's failing? (tests, type coverage, PHPStan, etc.)
3. Should I delete existing tests and start fresh, or fix them?

Based on user's answer:

### Option A: Fix Existing Tests
1. Run `composer test` to see all failures
2. Read existing test files
3. Read implementation code (Requests, Resources, Data classes)
4. Identify issues (wrong assertions, outdated fixtures, missing coverage)
5. Fix tests one by one
6. Ensure 100% code and type coverage
7. Run `composer test` until all pass

### Option B: Delete and Rewrite Tests
1. Delete existing test files for this resource
2. Read implementation code (Requests, Resources, Data classes)
3. Read API documentation from `.ai/ortto/{resource}/`
4. Start fresh with **Step 2: Exploration Phase** from Phase 3
   - Write integration tests hitting live API
   - Handle resource state issues
   - Convert to fixture-based tests
5. Run `composer test` until all pass
6. Update task list

### Option C: Rethink Implementation
1. Review existing code and tests
2. Read API documentation
3. Identify architectural issues
4. Propose refactoring plan
5. Get user approval
6. Implement changes
7. Update tests accordingly

After fixes pass:
- Update task list in `.ai/specs/{resource}/`
- Ask about committing changes

Refer to `.claude/sdk-testing-agent.md` for detailed testing patterns and quality standards.
