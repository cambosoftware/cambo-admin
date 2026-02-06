# Contributing to CamboAdmin

Thank you for considering contributing to CamboAdmin! This document provides guidelines and instructions for contributing.

## Code of Conduct

Please be respectful and professional in all interactions.

## How to Contribute

### Reporting Bugs

Before creating bug reports, please check existing issues to avoid duplicates.

When creating a bug report, include:
- PHP version
- Laravel version
- CamboAdmin version
- Steps to reproduce
- Expected behavior
- Actual behavior
- Screenshots if applicable

### Suggesting Features

Feature suggestions are welcome! Please:
- Check if the feature has already been suggested
- Provide a clear description of the feature
- Explain the use case
- Consider the impact on existing functionality

### Pull Requests

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Make your changes
4. Run the tests (`./vendor/bin/phpunit`)
5. Commit your changes (`git commit -m 'Add amazing feature'`)
6. Push to the branch (`git push origin feature/amazing-feature`)
7. Open a Pull Request

### Coding Standards

- Follow PSR-12 for PHP code
- Use Vue 3 Composition API for Vue components
- Write tests for new features
- Document public methods and components

### Testing

```bash
# Run all tests
./vendor/bin/phpunit

# Run specific test
./vendor/bin/phpunit --filter TestName
```

### Vue Components

When creating Vue components:
- Use `<script setup>` syntax
- Define props with types and defaults
- Document props, emits, and slots
- Follow the existing component structure

### Documentation

- Update README.md for significant changes
- Add JSDoc comments to JavaScript/Vue code
- Add PHPDoc comments to PHP code

## Development Setup

1. Clone the repository
2. Install dependencies:
   ```bash
   composer install
   npm install
   ```
3. Run tests to ensure everything works:
   ```bash
   ./vendor/bin/phpunit
   ```

## Questions?

If you have questions, please open an issue or contact us at contact@cambosoftware.com.

Thank you for contributing!
