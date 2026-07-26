# Project

Modern Point of Sale (POS) System

---

# Technology Stack

Backend
- Laravel 13
- PHP 8.4+
- MySQL / MariaDB

Frontend
- React 19
- TypeScript
- Inertia.js
- Tailwind CSS v4
- shadcn/ui

Packages
- Laravel Fortify
- Google Authenticator (TOTP MFA)
- Spatie Permission
- Spatie Media Library
- Spatie Activity Log

---

# Purpose

This project is a modern Point of Sale system designed for:

- Restaurants
- Coffee Shops
- Cafes
- Bakeries
- Food Courts
- Small Businesses
- Retail Stores

The system must be modular, scalable, maintainable, and production-ready.

The project should be designed so future expansion does NOT require redesigning the core architecture.

---

# User Roles

Describe every user role.

Include:

Guest Customer

Registered Customer (optional)

Cashier

Kitchen Staff

Administrator

Owner

Explain responsibilities of each role.

---

# Applications

Describe every application.

Customer POS

Admin Dashboard

Kitchen Display

Explain their responsibilities.

---

# Customer Experience

Customer does NOT need an account.

Customer can:

- Browse products
- Search products
- Select product options
- Add notes
- Add items to cart
- Checkout
- Receive queue/order number

---

# Authentication

Customer POS requires NO login.

Administration requires authentication.

Administrator login must use:

- Email
- Password
- Google Authenticator MFA

---

# Store Concept

The system supports one store initially but the architecture must be prepared for future multi-store support.

Store contains:

Logo

Cover

Name

Description

Address

Google Maps

WhatsApp

Email

Business Hours

Currency

Timezone

Tax Configuration

Receipt Footer

---

# Product Philosophy

Products are designed to be generic.

Never create dedicated product fields such as:

- Size
- Topping
- Sugar
- Ice
- Spicy Level

Instead, every customization must use Product Option Groups and Product Option Items.

Explain why this architecture is preferred.

---

# Product Concept

Explain:

Categories

Multiple Images

Thumbnail

Description

SKU

Barcode

Base Price

Cost Price

Stock

Minimum Stock

Visibility

Status

Featured

Sorting

Product Options

---

# Product Option Philosophy

Explain the concept of Product Option Groups.

Examples:

Size

Sugar

Ice

Sauce

Topping

Spicy

Explain Product Option Items.

Explain:

Required selection

Optional selection

Minimum selection

Maximum selection

Additional price

Optional stock

Images

Explain why this system replaces dedicated tables.

---

# Shopping Cart

Explain shopping cart behavior.

Support:

Product

Quantity

Selected Options

Additional Prices

Customer Notes

---

# Order Philosophy

Orders must use snapshot strategy.

Once an order is created, all product information must be copied into the order.

Future product changes must NEVER modify historical orders.

Explain why.

---

# Payment

Describe payment concept.

Cash

QRIS

Transfer

Debit

Credit Card

Digital Wallet

---

# Kitchen Workflow

Describe order lifecycle.

Pending

Preparing

Ready

Completed

Cancelled

---

# Reports

Reports must always use historical order data instead of current product data.

Explain why.

---

# Core Design Principles

Explain all principles.

Examples:

Generic over Special Case

Scalability

Maintainability

SOLID

DRY

KISS

Modularity

Reusable Components

Strict Typing

Snapshot Transactions

Auditability

Security First

Performance First

Accessibility

---

# Security Principles

Explain:

Authentication

Authorization

Policy

Validation

Rate Limiting

CSRF

Activity Logging

MFA

Secure File Upload

---

# Future Expansion

Design the architecture to support future modules without redesigning the database.

Potential future modules include:

Inventory

Suppliers

Purchasing

Recipe / Bill of Materials

Kitchen Printer

Thermal Printer

QRIS Integration

Payment Gateway

Customer Loyalty

Membership

Coupons

Promotions

Gift Cards

Reservations

Table Management

Delivery

Multi Branch

Public API

Mobile Apps

Progressive Web App

SaaS

Offline Mode

Analytics

Business Intelligence

---

# AI Guidance

End the document with a section specifically written for AI coding agents.

Explain:

Project philosophy

Architectural expectations

Design consistency

Maintainability goals

Business rules

What should never be violated while implementing new features.

The final result should be a professional Software Project Specification, not a README.