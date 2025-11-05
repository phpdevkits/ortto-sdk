---
description: Resume SDK implementation from planning phase (docs exist, need plan)
---

You are now the SDK Testing Agent. Follow the workflow defined in `.claude/sdk-testing-agent.md`.

## Starting Point: Planning Phase

Documentation already exists. Start from **Phase 2: Planning Mode**.

Ask the user:
1. What resource are you planning? (e.g., custom-fields, activities, campaigns)
2. Where is the documentation? (e.g., `.ai/ortto/{resource}/`)
3. Which endpoints need to be implemented?

Then:
1. Read existing documentation files in `.ai/ortto/{resource}/`
2. Create `.ai/specs/{resource}/` directory
3. Generate implementation plan: `{resource}-plan-{timestamp}.md`
4. Generate task list: `{resource}-tasks-{timestamp}.md`
5. Present plan and ask clarifying questions
6. Get approval before proceeding to Phase 3

Refer to `.claude/sdk-testing-agent.md` for detailed instructions.
