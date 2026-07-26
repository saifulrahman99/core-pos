# Architecture

## Purpose

This document defines the software architecture used throughout the project.

All implementations must follow this architecture to ensure consistency, maintainability, scalability, and code quality.

This architecture is mandatory for every module.

---

# Architecture Style

The application follows a layered architecture with clear separation of responsibilities.

```text
HTTP Request
    │
    ▼
Route
    │
    ▼
Controller
    │
    ▼
Form Request
    │
    ▼
Service
    │
    ▼
DTO
    │
    ▼
Models
    │
    ▼
Database
```

Business logic must never bypass this flow.

---

# Core Principles

Every layer has exactly one responsibility.

Business logic must exist only inside Services.

Controllers coordinate requests.

Models represent data.

DTOs transfer data.

Resources transform responses.

Policies authorize actions.

Form Requests validate incoming data.

---

# Directory Structure

The project should follow Laravel's default structure while introducing additional layers only where necessary.

Core business logic belongs inside dedicated Service classes.

DTOs must be grouped by domain.

Enums should be grouped by domain.

Policies should follow Laravel conventions.

---

# Controller

Controllers are thin.

Controllers are responsible for:

- Receiving requests
- Calling Form Requests
- Calling Services
- Returning Responses

Controllers must NOT:

- Query multiple models
- Contain business rules
- Perform calculations
- Manipulate transactions
- Handle permissions manually
- Validate manually

---

# Form Request

Every create and update operation must use a dedicated Form Request.

Responsibilities:

- Validation
- Authorization (when appropriate)
- Sanitizing simple input

Form Requests must never contain business logic.

---

# Service Layer

Every business process belongs inside a Service.

Examples:

- Creating products
- Updating orders
- Completing payments
- Calculating totals
- Updating stock
- Applying promotions

Services may:

- Call multiple models
- Execute database transactions
- Dispatch events
- Dispatch jobs
- Throw business exceptions

Services must never generate UI.

---

# DTO (Data Transfer Object)

DTOs provide strongly typed data between layers.

Every complex operation should receive a DTO instead of raw Request objects.

DTOs should:

- Be immutable whenever possible
- Contain validated data only
- Never access the database
- Never contain business logic

---

# Models

Models represent the persistence layer.

Models are responsible for:

- Relationships
- Attribute casting
- Scopes
- Accessors
- Mutators

Models must NOT:

- Perform business workflows
- Send notifications
- Execute complex calculations
- Coordinate multiple entities

---

# Eloquent Relationships

Always prefer Eloquent Relationships.

Avoid manual joins unless performance requires it.

Always use eager loading when relationships are displayed.

Avoid N+1 queries.

---

# Policies

Every protected resource must have a Policy.

Never authorize directly inside Controllers.

Authorization must always use:

- Policies
- Gates (only when appropriate)

---

# Resources

Every API or Inertia response should use Laravel Resources whenever data transformation is required.

Never expose raw models directly to the frontend.

Resources should:

- Format output
- Hide internal fields
- Keep response structures consistent

---

# Enums

Magic strings are prohibited.

Statuses should always use Enums.

Examples include:

- Order Status
- Payment Status
- User Status
- Product Status

Enums improve readability and prevent invalid values.

---

# Events

Events should be used when something meaningful happens.

Examples:

- Order Created
- Payment Completed
- Product Created

Events should not contain business logic.

---

# Listeners

Listeners react to Events.

Examples:

- Send notifications
- Write activity logs
- Update analytics
- Trigger integrations

Listeners should remain independent.

---

# Jobs

Heavy operations should run asynchronously.

Examples:

- Email
- Image processing
- PDF generation
- Report generation
- Synchronization

Never block HTTP requests with long-running tasks.

---

# Notifications

Notifications should use Laravel Notification channels.

Notification logic must remain outside Controllers.

---

# Database Transactions

Whenever multiple tables are modified within a single business operation, use database transactions.

Either the entire operation succeeds, or the entire operation rolls back.

Never leave partial data.

---

# Error Handling

Business errors should use custom exceptions.

Never expose internal exceptions to end users.

Provide meaningful and user-friendly messages.

Log unexpected exceptions.

---

# Dependency Injection

Always use dependency injection.

Never instantiate Services manually inside Controllers.

Prefer constructor injection.

---

# Configuration

Do not hardcode configuration values.

Use:

- config()
- environment variables

Business rules must not depend directly on environment variables.

---

# Reusability

Duplicate code is prohibited.

If logic is reused multiple times, extract it into:

- Service
- Support class
- Action
- Trait (only when appropriate)

---

# Scalability

The architecture must support future modules without major refactoring.

Future expansion includes:

- Multi Store
- Inventory
- Supplier
- Purchase
- Loyalty
- Mobile Application
- Public API
- SaaS

Core architecture should remain unchanged.

---

# Performance

Prefer eager loading.

Paginate large datasets.

Cache expensive operations when appropriate.

Avoid unnecessary database queries.

Avoid repeated calculations.

Optimize only when necessary without sacrificing readability.

---

# Security

Never trust client input.

Always validate.

Always authorize.

Never expose sensitive information.

Always protect administrative features.

---

# Frontend Architecture

Frontend follows component-based architecture.

Pages coordinate UI.

Reusable Components contain presentation logic.

Business logic should remain minimal on the frontend.

Server remains the source of truth.

---

# Design Philosophy

The application prioritizes:

- Simplicity
- Maintainability
- Readability
- Predictability
- Scalability
- Consistency

Code should always be easy to understand by another developer.

Readable code is preferred over clever code.

Long-term maintainability is more important than short-term convenience.

---

# Architectural Rules

These rules must never be violated.

- Business logic belongs only inside Services.
- Controllers remain thin.
- Validation always uses Form Requests.
- Authorization always uses Policies.
- Status values always use Enums.
- Responses use Resources.
- Relationships use Eloquent.
- Heavy processes use Jobs.
- Business workflows use database transactions.
- Avoid duplicate code.
- Avoid magic strings.
- Avoid hardcoded values.
- Keep modules independent whenever possible.
- Maintain consistency across the entire application.