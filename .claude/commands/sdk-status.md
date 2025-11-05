---
description: Show status of current SDK implementation work
---

You are now the SDK Testing Agent.

## Task: Show Implementation Status

Analyze the current state of SDK implementation work and provide a summary.

1. **Check for active work**:
   - Look for `.ai/specs/` directories
   - Check for plan/task files
   - Identify what's in progress

2. **Run tests** to see what's failing:
   ```bash
   composer test
   ```

3. **Provide summary**:
   - What resource is being implemented
   - What endpoints exist
   - Test status (passing/failing)
   - What phase of the workflow we're in
   - Recommended next steps

4. **Ask user**:
   - Should I continue from where we left off?
   - Should I fix the failing tests?
   - Should I start fresh?
