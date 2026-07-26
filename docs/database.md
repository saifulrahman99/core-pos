# Database Design Principles

## Purpose

This document defines the database design principles for the project.

It does not describe specific tables.

It defines the rules that every table, relationship, and migration must follow.

The database must remain maintainable, scalable, and extensible.

Future modules should require minimal schema changes.

---

# Database Philosophy

The database is designed around business domains.

Every table should represent a business entity.

Avoid creating tables that only solve one specific use case.

Prefer generic structures that support future expansion.

The schema should evolve without requiring redesign.

---

# Design Principles

Always prioritize:

- Simplicity
- Data Integrity
- Scalability
- Consistency
- Maintainability

Avoid unnecessary complexity.

---

# Naming Convention

All database objects must use snake_case.

Examples:

products

product_categories

order_items

payment_methods

Never use PascalCase or camelCase for database objects.

---

# Primary Keys

Every table must use a primary key.

The default primary key is:

id

Auto-incrementing integers are acceptable.

UUIDs may be introduced in the future without affecting business logic.

---

# Foreign Keys

Always use foreign key constraints whenever relationships exist.

Foreign keys improve:

- Data integrity
- Consistency
- Reliability

Avoid orphan records.

---

# Relationships

Prefer normalized relationships.

Use:

One-to-One

One-to-Many

Many-to-Many

Only denormalize when a measurable performance benefit exists.

---

# Pivot Tables

Use pivot tables for many-to-many relationships.

Pivot tables may contain additional business attributes when necessary.

Examples include:

- Sort Order
- Additional Price
- Quantity
- Metadata

---

# Soft Deletes

Soft Deletes should be used for master data whenever appropriate.

Examples include:

Products

Categories

Customers

Users

Never use Soft Deletes for transactional history unless a specific business requirement exists.

---

# Historical Data

Historical business data must never be physically deleted.

Historical accuracy has higher priority than storage optimization.

Business history must remain reproducible.

---

# Snapshot Strategy

Transactional data always stores snapshots.

Orders must never depend on current Product data.

Snapshots preserve:

- Product Name
- Price
- Selected Options
- Additional Prices
- Tax Values
- Discounts

Historical transactions must remain unchanged forever.

---

# Immutable Transactions

Completed transactions are immutable.

Editing master data must never modify historical records.

Deleting master data must never remove historical transactions.

Reports must always remain historically accurate.

---

# Data Integrity

Data integrity is mandatory.

The database should never contain partially completed business operations.

All multi-table operations must use database transactions.

---

# Constraints

Use database constraints whenever possible.

Examples include:

Foreign Keys

Unique Constraints

Indexes

Check Constraints (when supported)

Business rules should be reinforced at both the application and database levels where appropriate.

---

# Unique Values

Business identifiers should remain unique whenever required.

Examples include:

SKU

Barcode

User Email

Store Slug

Uniqueness should be enforced by the database.

---

# Indexing

Create indexes for:

Foreign Keys

Frequently searched columns

Frequently filtered columns

Frequently sorted columns

Avoid unnecessary indexes.

Every index should provide measurable value.

---

# Nullable Columns

Only make columns nullable when the business truly allows missing values.

Avoid excessive nullable fields.

Required business information should always be required.

---

# Monetary Values

Store all monetary values using fixed precision numeric types.

Never use floating-point data types for financial calculations.

Monetary calculations must always be deterministic.

---

# Quantities

Stock and quantities must never become negative.

Validation belongs in business logic.

Database constraints may reinforce integrity where appropriate.

---

# Status Columns

Status values should use application Enums.

The database stores only valid status values.

Avoid magic strings.

Avoid inconsistent status naming.

---

# Date and Time

Store all timestamps consistently.

Application timezone handling should remain centralized.

Avoid storing formatted date strings.

---

# Auditability

Important business operations should be traceable.

The database should support:

Created By

Updated By

Activity Logs

Future auditing requirements should not require redesign.

---

# Extensibility

The schema should support future modules without major restructuring.

Examples include:

Inventory

Suppliers

Purchasing

Recipes

Loyalty

Membership

Coupons

Promotions

Reservations

Delivery

Multi Store

Public API

SaaS

Future growth should be anticipated during schema design.

---

# Product Design

Products must remain generic.

Never create dedicated schema for:

Sizes

Sugar Levels

Ice Levels

Spicy Levels

Toppings

Sauces

Extras

All customizations belong to the Product Option system.

---

# Order Design

Orders represent completed business transactions.

Orders store historical snapshots.

Order data is the primary source for:

Reports

Revenue

Analytics

Sales History

Never rebuild historical values from master tables.

---

# Performance

Optimize only when necessary.

Prefer readable schemas over premature optimization.

Use indexes appropriately.

Avoid redundant columns.

Avoid duplicated data unless required for snapshot purposes.

---

# Security

Sensitive information must be protected.

Avoid storing unnecessary confidential data.

Passwords must never be stored in plain text.

Authentication secrets must remain encrypted.

---

# Migrations

Every migration should be:

Atomic

Reversible

Consistent

Readable

Never modify existing migrations that have already been executed in production.

Use new migrations for schema evolution.

---

# Future Compatibility

The database should support future architectural changes without redesign.

Examples include:

Microservices

Read Replicas

Caching

Background Workers

API Integrations

Offline Synchronization

Multi-Region Deployment

The database should remain stable as the application evolves.

---

# Final Principles

Every database decision should follow these priorities:

1. Data Integrity
2. Historical Accuracy
3. Simplicity
4. Maintainability
5. Scalability
6. Performance

When trade-offs exist, prioritize long-term maintainability over short-term convenience.