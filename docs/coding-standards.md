# Coding Standards

## Purpose

This document defines the coding standards for the entire project.

Every generated file must follow these standards.

Consistency is more important than personal preference.

Always prioritize readability, maintainability, scalability, and simplicity.

---

# General Principles

Follow:

- SOLID
- DRY
- KISS
- Clean Code
- PSR-12

Write code for humans first.

Readable code is preferred over clever code.

Avoid unnecessary abstractions.

---

# PHP

Always use:

- PHP 8.4+
- Strict typing
- Constructor Property Promotion
- Readonly properties where appropriate
- Return types
- Typed properties

Avoid legacy PHP syntax.

---

# Laravel

Always follow Laravel Best Practices.

Prefer Laravel conventions over custom implementations.

Use Laravel features whenever possible.

Avoid reinventing framework functionality.

---

# Naming Convention

## Classes

Use PascalCase.

Examples

ProductService

CreateOrderAction

StoreController

---

## Methods

Use camelCase.

Examples

createProduct()

updateStock()

calculateTotal()

---

## Variables

Use camelCase.

Examples

$product

$orderItem

$totalPrice

---

## Database

Use snake_case.

Examples

product_option_groups

order_items

payment_status

---

## Constants

Use UPPER_SNAKE_CASE.

Examples

MAX_UPLOAD_SIZE

DEFAULT_PAGE_SIZE

---

## Routes

Use kebab-case.

Examples

products

product-options

payment-methods

---

# Controllers

Controllers must remain thin.

Controllers are responsible only for:

- Receiving requests
- Calling Form Requests
- Calling Services
- Returning Responses

Controllers must never:

- Contain business logic
- Perform calculations
- Handle transactions
- Authorize manually
- Validate manually

---

# Form Requests

Always use Form Requests.

Never validate directly inside Controllers.

Validation messages should be user-friendly.

Complex business validation belongs inside Services.

---

# Services

Business logic belongs only inside Services.

Services should be focused on one responsibility.

Avoid creating large Service classes.

Split responsibilities when necessary.

---

# DTO

Every complex business operation should use a DTO.

DTOs should:

- Receive validated data
- Be immutable when possible
- Never query the database
- Never contain business logic

---

# Models

Models represent persistence.

Models should contain only:

- Relationships
- Scopes
- Accessors
- Mutators
- Casts

Models should not coordinate business workflows.

---

# Policies

Always use Policies.

Never authorize inside Controllers.

Authorization should remain centralized.

---

# Resources

Use Laravel Resources whenever returning structured data.

Never expose internal attributes unnecessarily.

Maintain consistent response structures.

---

# Enums

Always use Enums instead of magic strings.

Examples include:

- Order Status
- Payment Status
- Product Status
- User Status

Avoid string comparisons throughout the codebase.

---

# Database

Always use:

- Foreign Keys
- Indexes
- Database Transactions

Use Soft Deletes for master data when appropriate.

Historical transaction data should remain permanent.

---

# Transactions

Whenever multiple tables are modified within one business operation:

Always use database transactions.

Never leave partial updates.

---

# Relationships

Always use Eloquent Relationships.

Prefer eager loading.

Avoid N+1 queries.

Avoid unnecessary joins.

---

# Queries

Prefer Eloquent.

Use Query Builder only when necessary.

Raw SQL should be the last option.

Always optimize queries for readability first.

---

# Exceptions

Create custom exceptions for business errors.

Never expose internal exception details to users.

Log unexpected exceptions.

---

# Logging

Use structured logging.

Do not log sensitive information.

Activity Logs should record important business actions.

---

# Events

Use Events for domain events.

Examples:

Order Created

Payment Completed

Product Created

Events should not contain business logic.

---

# Jobs

Use Jobs for:

- Emails
- Image processing
- Report generation
- Long-running operations

Avoid blocking HTTP requests.

---

# Notifications

Use Laravel Notifications.

Keep notification logic separate from business logic.

---

# Configuration

Never hardcode configuration values.

Use config().

Use environment variables only inside configuration files.

---

# File Uploads

Always validate:

- MIME Type
- File Size

Store uploaded files using Laravel Storage.

Never trust client-provided file names.

---

# React

Always use:

- Functional Components
- TypeScript
- React Hooks

Avoid Class Components.

---

# TypeScript

Enable strict mode.

Avoid using:

any

Prefer explicit typing.

Create reusable interfaces.

Use Enums only when appropriate.

---

# Components

Components should be reusable.

Each component should have one responsibility.

Avoid duplicate UI.

Extract shared components early.

---

# Pages

Pages coordinate UI.

Business logic should remain on the backend whenever possible.

Avoid large page components.

---

# Styling

Use only:

Tailwind CSS v4

shadcn/ui

Avoid inline styles.

Avoid custom CSS unless absolutely necessary.

---

# Forms

Always use reusable form components.

Display validation messages consistently.

Support keyboard navigation.

---

# Tables

Use TanStack Table.

Support:

- Search
- Sorting
- Filtering
- Pagination

Keep table components reusable.

---

# Icons

Use Lucide Icons only.

Do not mix multiple icon libraries.

---

# Imports

Remove unused imports.

Group imports logically.

Prefer absolute imports when configured.

---

# Comments

Write comments only when necessary.

Code should explain itself.

Avoid obvious comments.

---

# Testing

Every business feature should include tests.

Prioritize:

- Feature Tests
- Unit Tests

Business logic should be testable independently.

---

# Performance

Use eager loading.

Paginate large datasets.

Avoid repeated database queries.

Cache expensive operations when appropriate.

Measure performance before optimizing.

---

# Security

Never trust user input.

Always validate.

Always authorize.

Escape output when necessary.

Protect sensitive routes.

---

# Git

Keep commits focused.

One logical change per commit.

Write meaningful commit messages.

Avoid mixing unrelated changes.

---

# Code Review Checklist

Before considering a feature complete, verify:

- Code follows project architecture.
- Business logic is inside Services.
- Validation uses Form Requests.
- Authorization uses Policies.
- Responses use Resources.
- Statuses use Enums.
- Database operations use Transactions when required.
- No duplicated code exists.
- No unused code remains.
- Tests pass.
- Code is readable and maintainable.

---

# Final Rule

When multiple implementation options exist:

Choose the solution that is:

- Easiest to understand
- Easiest to maintain
- Most consistent with the existing architecture
- Most extensible for future development

Consistency across the project is more important than individual optimization.