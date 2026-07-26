# Technology Stack

## Purpose

This document defines the official technology stack of the project.

All implementations must use the technologies listed below.

Avoid introducing new dependencies unless they provide significant long-term value.

Consistency is preferred over experimentation.

---

# Backend

Framework

Laravel 13

Language

PHP 8.4+

Database

MySQL 8+

or

MariaDB

Queue

Laravel Queue

Cache

Laravel Cache

Scheduler

Laravel Scheduler

Storage

Laravel Filesystem

Authentication

Laravel Fortify

Authorization

Laravel Policies

Permission

Spatie Laravel Permission

Media Management

Spatie Media Library

Activity Logging

Spatie Activity Log

---

# Frontend

Framework

React 19

Language

TypeScript

Routing

Inertia.js

Styling

Tailwind CSS v4

UI Components

shadcn/ui

Icons

Lucide React

Table

TanStack Table

Forms

React Hook Form

Validation

Zod

Theme

next-themes (or equivalent)

---

# Development

Package Manager

pnpm

Version Control

Git

Linting

ESLint

Formatting

Prettier

Testing

PHPUnit

Pest (optional)

Vitest (Frontend)

---

# Build Tools

Vite

---

# Images

Use modern image formats whenever possible.

Support responsive images.

Generate thumbnails automatically.

---

# API

REST API

JSON

Laravel Resources

---

# Security

Google Authenticator (TOTP MFA)

CSRF Protection

Rate Limiting

Policies

Validation

Activity Logs

---

# File Upload

Store files using Laravel Storage.

Never access uploaded files directly.

Always generate URLs through the storage layer.

---

# UI

Use only:

Tailwind CSS

shadcn/ui

Lucide Icons

Avoid introducing additional UI frameworks.

---

# Components

Create reusable components.

Avoid duplicated UI.

Prefer composition.

---

# Styling

Do not write large custom CSS files.

Prefer Tailwind utilities.

Create reusable variants when necessary.

---

# Future Compatibility

The technology stack should remain compatible with future support for:

- Multi Store
- Inventory
- Purchasing
- Kitchen Display
- QRIS
- Payment Gateway
- Mobile App
- Progressive Web App
- Public API
- SaaS

---

# Dependency Rules

Before adding a new package, consider:

- Is it actively maintained?
- Does Laravel already provide this feature?
- Does an existing package already solve this problem?
- Will it increase long-term maintenance?

Avoid unnecessary dependencies.

Prefer fewer, high-quality packages.

---

# Final Principles

Use the official stack consistently.

Avoid mixing libraries with overlapping responsibilities.

Keep the project lightweight, maintainable, and production-ready.