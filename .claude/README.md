# SDK Testing Agent - Usage Guide

This directory contains the SDK Testing Agent configuration and slash commands for implementing Ortto SDK endpoints.

## Quick Start

Use these slash commands to work with the SDK Testing Agent:

### `/sdk-implement` - Start Fresh (Full Workflow)
Use when implementing a **brand new resource** from scratch.

**Workflow**: Documentation → Planning → Implementation → Completion

**Example**:
```
/sdk-implement
```
Agent will ask for: resource name, documentation URL, requirements

---

### `/sdk-plan` - Resume from Planning
Use when **documentation exists** but you need to create the implementation plan.

**Workflow**: Planning → Implementation → Completion

**Example**:
```
/sdk-plan
```
Agent will read docs from `.ai/ortto/{resource}/` and create plan

---

### `/sdk-code` - Resume from Implementation
Use when **docs and plan exist** but you need to write code and tests.

**Workflow**: Implementation → Completion

**Example**:
```
/sdk-code
```
Agent will read plan from `.ai/specs/{resource}/` and start coding

---

### `/sdk-fix` - Fix Failing Implementation
Use when **code and tests exist but are failing**.

**Workflow**: Diagnose → Fix → Quality Checks

**Example**:
```
/sdk-fix
```
Agent will analyze failures and ask if you want to:
- Fix existing tests
- Delete and rewrite tests from scratch
- Rethink the implementation

---

### `/sdk-status` - Check Implementation Status
Use to see **what's currently in progress** and get recommendations.

**Example**:
```
/sdk-status
```
Agent will show: active work, test status, next steps

---

## Current Situation: Custom Fields

Based on test output, you have:
- ✅ Code implemented (Enums, Data classes, Requests, Resources)
- ✅ Tests written
- ❌ Tests failing with 404/423 errors (hitting live API incorrectly)

### Recommended Next Steps

**Option 1: Fix Tests (Recommended)**
```
/sdk-fix
```
Then tell agent:
- "Custom fields resource"
- "Tests are failing with 404/423 errors"
- "Delete existing Resource tests and rewrite them from scratch with proper integration tests first"

**Option 2: Check Status First**
```
/sdk-status
```
Get a full analysis, then decide

---

## Agent Workflow Phases

### Phase 1: Documentation Gathering
- Ask for API docs (URL/OpenAPI/files)
- Create `.ai/ortto/{resource}/*.md` files
- One file per endpoint

### Phase 2: Planning Mode
- Read documentation
- Create `.ai/specs/{resource}/` directory
- Generate `{resource}-plan-{timestamp}.md`
- Generate `{resource}-tasks-{timestamp}.md`
- Ask clarifying questions
- Get approval

### Phase 3: Implementation (Per Endpoint)
1. **Code**: Enums → Data → Request → Resource
2. **Explore**: Integration tests with live API (`->group('integration')`)
3. **Fixture**: Convert to MockClient with fixtures
4. **Quality**: Run `composer test` (100% coverage required)
5. **Tasks**: Update task list
6. **Commit**: Ask user about git commit/push

### Phase 4: Completion
- Final `composer test`
- Update `CLAUDE.md` if needed
- Summarize work
- Offer to create PR

---

## Testing Philosophy

### Exploration Phase (Live API)
- Start with `->group('integration')` tests
- Hit real API to understand behavior
- Handle resource state issues (already exists, must archive first, etc.)
- Ask user for IDs when needed
- Document actual API responses

### Fixture Phase (Mocked)
- Convert to MockClient tests
- Auto-record fixtures from real API
- Update test data to match fixture IDs
- Test **actual values**, not just types
- Remove time-dependent assertions

### Quality Requirements
- 100% code coverage
- 100% type coverage
- PHPStan max level
- Pint formatting
- No typos (Peck)
- Rector refactoring compliance

---

## Common Issues

### 404 Errors
- Wrong endpoint URL
- Missing `scope` parameter (person vs account)
- Incorrect API base URL for region

### 423 Errors
- Rate limited
- Resource locked
- Account restrictions
- Use mocks/fixtures instead of live API

### Resource Already Exists
- Normal for repeated test runs
- Accept both "created" and "already_exists" statuses
- Use unique identifiers (timestamps)

### Resource Dependencies
- Example: Must archive person before delete
- Ask user for pre-existing resource IDs
- Or implement full lifecycle (create → archive → delete)

---

## File Structure

```
.claude/
├── sdk-testing-agent.md       # Main agent configuration
├── README.md                   # This file
└── commands/
    ├── sdk-implement.md        # Start from docs
    ├── sdk-plan.md             # Start from planning
    ├── sdk-code.md             # Start from implementation
    ├── sdk-fix.md              # Fix failing tests
    └── sdk-status.md           # Check status

.ai/
├── ortto/                      # API documentation
│   ├── person/
│   ├── account/
│   └── {resource}/
└── specs/                      # Implementation plans
    └── {resource}/
        ├── {resource}-plan-{timestamp}.md
        └── {resource}-tasks-{timestamp}.md
```

---

## Tips

1. **Always start with docs**: Never implement without understanding the API
2. **Plan before coding**: Create plan and get approval first
3. **One endpoint at a time**: Complete each fully before moving on
4. **Test with live API first**: Understand real behavior before mocking
5. **Quality is non-negotiable**: All checks must pass
6. **Commit per endpoint**: Small, atomic commits
7. **Ask questions**: Agent will ask for IDs, clarifications, approvals
8. **Handle reality**: APIs have state, dependencies, rate limits

---

## For Your Custom Fields Issue

Since tests are failing with 404/423 errors, I recommend:

```bash
# Use the fix command
/sdk-fix
```

Then choose: **"Delete and rewrite tests"**

The agent will:
1. Keep your implementation code (Enums, Data, Requests, Resources)
2. Delete the failing test files
3. Start fresh with integration tests
4. Hit live API to understand actual behavior
5. Convert to fixtures once working
6. Ensure 100% coverage

This is exactly what the workflow is designed for!
