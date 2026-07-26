# Business Rules

## Purpose

This document defines the global business rules of the POS system.

These rules describe how the business operates.

They are independent from implementation details.

Every feature, module, service, and database design must comply with these rules.

If an implementation conflicts with this document, this document takes precedence.

---

# Business Philosophy

The system is designed to be:

- Generic
- Modular
- Scalable
- Maintainable
- Production Ready

Avoid creating business logic that only solves one specific case.

Every feature should be reusable whenever possible.

---

# Store

The application initially supports one store.

However, the architecture must always be prepared for future multi-store support.

Store information represents the identity of the business.

Examples include:

- Logo
- Cover Image
- Store Name
- Description
- Address
- Google Maps
- WhatsApp
- Email
- Business Hours
- Currency
- Tax Configuration
- Receipt Footer

Store information is editable without affecting historical transactions.

---

# Customer

Customers are not required to create an account.

Guest checkout is the default behavior.

Customer registration is optional for future membership features.

Customers may:

- Browse products
- Search products
- Select product options
- Add notes
- Add products to cart
- Checkout

Customers cannot access administrative functions.

---

# Authentication

Customer POS does not require authentication.

Administrative users must authenticate using:

- Email
- Password
- Google Authenticator (TOTP MFA)

MFA is mandatory for all administrative roles.

---

# User Roles

The application supports the following roles.

Guest

Customer

Cashier

Kitchen

Administrator

Owner

Every role has different permissions.

Permissions must always be enforced using Policies.

---

# Categories

Products belong to categories.

Categories are managed dynamically.

Categories may be enabled or disabled.

Deleting a category must never remove historical order data.

---

# Products

A product represents an item that can be sold.

Products contain business information only.

Products may include:

- Name
- SKU
- Barcode
- Description
- Base Price
- Cost Price
- Stock
- Images
- Categories
- Product Options

Products may be temporarily hidden without deleting them.

Products should support soft deletion.

---

# Product Images

Products support multiple images.

One image is designated as the primary thumbnail.

Additional images are used for galleries.

Historical orders must never depend on product images.

---

# Product Option Philosophy

The system must NEVER create dedicated business tables such as:

- Sizes
- Toppings
- Sugar Levels
- Ice Levels
- Spice Levels
- Sauces

Instead, every customization must use the Product Option system.

This allows unlimited flexibility without modifying the database.

---

# Product Option Groups

A Product Option Group represents a customization category.

Examples:

- Size
- Sugar
- Ice
- Sauce
- Spicy
- Topping
- Extra

Groups define how customers customize products.

Groups support:

- Required selection
- Optional selection
- Minimum selection
- Maximum selection

---

# Product Option Items

Each Product Option Group contains multiple selectable items.

Examples:

Size

- Small
- Medium
- Large

Sugar

- Normal
- Less Sugar
- No Sugar

Spicy

- Level 0
- Level 1
- Level 2
- Level 3

Every item may define:

- Additional Price
- Optional Stock
- Image
- Sort Order

---

# Product Availability

Products may become unavailable because:

- Disabled
- Out of Stock
- Hidden

Unavailable products must not be purchasable.

Historical orders remain unchanged.

---

# Pricing

Products have a base price.

Product Options may increase the final selling price.

Final price is calculated only during checkout.

The final calculated value must be stored permanently in the Order.

Future price changes must never affect previous transactions.

---

# Stock

Stock belongs to products.

Product Options may optionally maintain their own stock if required.

Stock must never become negative.

Every stock change should be traceable.

Future inventory modules may extend stock behavior without changing existing product structures.

---

# Shopping Cart

A shopping cart is temporary.

Cart contents may contain:

- Product
- Quantity
- Selected Options
- Additional Prices
- Customer Notes

Cart data is not considered permanent business data.

---

# Customer Notes

Customers may attach notes to individual order items.

Examples:

- No onions
- Extra spicy
- Less ice

Notes belong to Order Items, not Products.

---

# Orders

An order represents a completed customer purchase.

Orders become historical business records.

Orders must never be modified in ways that change historical accuracy.

Only operational statuses may change.

---

# Snapshot Strategy

Orders always use snapshots.

When checkout occurs, the system copies all relevant information into the Order.

Examples:

Product Name

Product Price

Selected Options

Option Prices

Product SKU

Historical records must never depend on current Product data.

---

# Immutable History

Historical transaction data is immutable.

Editing products must never change previous orders.

Deleting products must never remove historical orders.

Changing option prices must never modify previous transactions.

Historical accuracy has higher priority than convenience.

---

# Order Status

Every order has a lifecycle.

Typical states include:

Pending

Confirmed

Preparing

Ready

Completed

Cancelled

Status changes must follow valid business flows.

Invalid transitions should be prevented.

---

# Kitchen Workflow

Kitchen staff interact only with operational orders.

Kitchen staff cannot modify:

- Products
- Categories
- Settings
- Users

Kitchen staff only update preparation status.

---

# Payments

Payments belong to Orders.

Supported payment methods may include:

- Cash
- QRIS
- Bank Transfer
- Debit Card
- Credit Card
- Digital Wallet

Additional payment methods should be extendable without redesign.

---

# Payment Status

Payment status is independent from order status.

Example:

Order may be:

Preparing

while Payment remains:

Pending

Business logic must treat these independently.

---

# Reports

Reports always use Order data.

Reports must never calculate sales using current Product data.

Historical reports must remain accurate forever.

---

# Discounts

Discounts affect Orders.

Discount calculations should be stored during checkout.

Future discount changes must not modify historical transactions.

---

# Taxes

Taxes are calculated during checkout.

Calculated tax values must be stored in the Order.

Tax configuration changes must never modify historical orders.

---

# Activity Logs

Administrative actions should be recorded.

Examples:

- Product Created
- Product Updated
- Product Deleted
- User Login
- Payment Updated

Activity Logs support auditing.

---

# Soft Delete

Master data should use Soft Deletes whenever appropriate.

Examples:

- Products
- Categories
- Customers

Historical transactional data should never be physically removed.

---

# Data Integrity

Every business operation must preserve data integrity.

Incomplete operations must roll back completely.

Partial business data is unacceptable.

---

# Future Expansion

Current business rules must support future modules without redesign.

Future modules include:

- Inventory
- Suppliers
- Purchasing
- Recipes
- Kitchen Printer
- Loyalty
- Membership
- Coupons
- Promotions
- QRIS Integration
- Payment Gateway
- Delivery
- Reservations
- Table Management
- Public API
- Mobile Application
- SaaS
- Multi Store

---

# Core Business Principles

The following principles must never be violated.

- Orders always use snapshots.
- Historical data is immutable.
- Product customization always uses Product Options.
- Customers may purchase without registration.
- Administrative access always requires MFA.
- Reports always use historical order data.
- Business rules belong inside Services.
- Every transaction must preserve data integrity.
- The system must remain modular.
- The system must remain scalable.
- The database should rarely require structural changes when introducing new business requirements.