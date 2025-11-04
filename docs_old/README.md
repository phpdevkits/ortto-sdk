# Ortto SDK Documentation

Simple, focused documentation for the Ortto SDK package.

## Documentation Structure

```
docs/
├── index.md                       # Main documentation home
├── introduction.md                # SDK overview and features
├── installation.md                # Installation and setup guide
└── testing/
    ├── overview.md                # Testing philosophy and tools
    ├── architecture-tests.md      # Testing with Lawman
    └── mocking.md                 # Mocking with Saloon MockClient
```

## Reading the Documentation

Start with [index.md](index.md) for an overview, then follow the guides in order:

1. **[Introduction](introduction.md)** - Learn what Ortto SDK is and why to use it
2. **[Installation](installation.md)** - Get set up in minutes
3. **[Testing Overview](testing/overview.md)** - Understand the testing approach
4. **[Architecture Tests](testing/architecture-tests.md)** - Use Lawman for Saloon architecture validation
5. **[Mocking](testing/mocking.md)** - Mock API responses using Saloon's MockClient

## Key Technologies

This SDK uses:

- **[Saloon](https://docs.saloon.dev/)** - Modern PHP HTTP client for API integrations
- **[Lawman](https://github.com/JonPurvis/lawman)** - PestPHP plugin for testing Saloon architecture
- **[Pest](https://pestphp.com/)** - Elegant PHP testing framework
- **[Orchestra Testbench](https://github.com/orchestral/testbench)** - Laravel package testing

## Contributing to Documentation

When updating documentation:

1. Keep it simple and focused
2. Include practical code examples
3. Link to official docs (Saloon, Lawman) for detailed information
4. Test code examples to ensure they work
5. Use clear, concise language

## Documentation Philosophy

- **Simple over comprehensive** - Focus on what developers need to know
- **Code over words** - Show examples, not just explanations
- **Links over duplication** - Link to official docs instead of duplicating
- **Practical over theoretical** - Real-world usage, not academic explanations
