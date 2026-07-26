# UI Guidelines

## Purpose

This document defines the visual design language and user experience standards for the entire application.

Every screen, component, and interaction must follow these guidelines.

The objective is to create a modern, minimal, clean, fast, and consistent Point of Sale system.

---

# Design Philosophy

The application should feel like a modern SaaS product instead of a traditional ERP system.

Prioritize:

- Simplicity
- Clarity
- Consistency
- Speed
- Accessibility
- Readability

Every interface should feel lightweight and intuitive.

---

# Design Inspiration

The visual language should be inspired by:

- Linear
- Vercel
- GitHub
- Stripe Dashboard
- Raycast
- Notion
- shadcn/ui

These products emphasize clarity over decoration.

---

# User Experience Principles

Every feature should require as few clicks as possible.

Frequently used actions must always be easy to access.

Reduce unnecessary navigation.

Avoid overwhelming users with too many options.

Progressive disclosure should be used whenever appropriate.

The interface should feel predictable.

---

# Visual Style

The application must use a clean and minimal visual style.

Prefer:

- Flat Design
- Soft Shadows
- Rounded Corners
- Large White Space
- Consistent Spacing
- Neutral Colors

Avoid:

- Heavy Borders
- Excessive Colors
- Decorative Graphics
- Visual Clutter
- Unnecessary Effects

---

# Color Philosophy

Use semantic colors only.

Primary

Blue

Success

Green

Warning

Amber

Danger

Red

Neutral

Slate

Avoid assigning random colors to components.

Colors should communicate meaning.

---

# Dark Mode

Dark Mode is a first-class feature.

It must not simply invert colors.

Every component should be designed specifically for dark mode.

Contrast and readability must remain excellent.

---

# Typography

Use:

Inter

Only.

Maintain a clear hierarchy.

Recommended weights:

400

500

600

700

Avoid using excessive font sizes.

Headings should be concise.

Body text should prioritize readability.

---

# Spacing

Use consistent spacing throughout the application.

Layouts should breathe.

Avoid crowded interfaces.

Maintain consistent padding and margins across pages.

Whitespace is part of the design.

---

# Border Radius

Use consistent rounded corners.

Recommended usage:

Cards

Medium

Buttons

Medium

Inputs

Medium

Dialogs

Large

Avoid mixing multiple corner styles.

---

# Shadows

Use subtle shadows.

Elevation should be minimal.

Prefer:

Border + Soft Shadow

instead of heavy floating effects.

---

# Icons

Use Lucide Icons exclusively.

Maintain consistent icon sizing.

Avoid mixing icon libraries.

Icons should improve recognition, not decoration.

---

# Buttons

Button hierarchy should remain consistent.

Primary

Main action.

Secondary

Alternative action.

Outline

Less prominent action.

Ghost

Low emphasis action.

Destructive

Dangerous action.

Link

Navigation action.

Do not create unnecessary button variants.

---

# Forms

Forms should be simple and easy to complete.

Every field should have:

- Label
- Validation
- Helper Text (when necessary)

Avoid placeholder-only inputs.

Validation messages should clearly explain the problem.

Required fields should be obvious.

---

# Inputs

Inputs should remain visually consistent.

Support:

- Disabled
- Readonly
- Error
- Success
- Focus

Focus states must always be visible.

---

# Dialogs

Use dialogs only for focused tasks.

Avoid large multi-step workflows inside dialogs.

Dialogs should remain simple.

Use Sheets when additional workspace is required.

---

# Tables

Use TanStack Table.

Support:

- Search
- Sorting
- Filtering
- Pagination
- Column Visibility
- Row Selection
- Bulk Actions

Tables should remain readable.

Avoid excessive columns.

---

# Cards

Cards should group related information.

Avoid placing unrelated content inside the same card.

Cards should remain lightweight.

---

# Navigation

Navigation should remain simple.

Avoid deeply nested menus.

Users should always know where they are.

Current navigation state must always be visible.

---

# Sidebar

Sidebar should contain:

Primary navigation only.

Avoid excessive nesting.

Group related modules logically.

Collapse support is recommended.

---

# Search

Search should be available wherever it improves productivity.

Search should prioritize speed.

Frequently used modules should support instant search.

---

# Dashboard

Dashboard should provide meaningful information.

Examples include:

Sales Summary

Today's Revenue

Order Count

Top Products

Recent Orders

Inventory Alerts

Do not overload the dashboard.

Every widget should provide value.

---

# POS Screen

The POS interface should prioritize speed.

Cashiers should complete orders with minimal interaction.

Frequently used actions should remain visible.

The interface should work efficiently with:

Mouse

Keyboard

Touch Screen

---

# Customer POS

Customer-facing screens should be visually simple.

Focus on:

Products

Categories

Search

Cart

Checkout

Avoid exposing unnecessary administrative information.

---

# Images

Images should:

Load efficiently.

Maintain aspect ratio.

Support lazy loading where appropriate.

Provide placeholders during loading.

Broken images should display graceful fallbacks.

---

# Empty States

Every page should provide a meaningful empty state.

Include:

Simple Illustration or Icon

Title

Short Description

Primary Action

Avoid blank pages.

---

# Loading States

Prefer Skeleton Loaders.

Use Spinners only for short blocking operations.

Avoid sudden layout shifts.

---

# Notifications

Notifications should be concise.

Support:

Success

Error

Warning

Information

Messages should clearly explain what happened.

---

# Animations

Animations should be subtle.

Purpose:

Improve usability.

Not decoration.

Keep transitions fast.

Avoid long or distracting animations.

---

# Responsive Design

Every page must support:

Desktop

Tablet

Mobile

Layouts should adapt naturally.

Avoid horizontal scrolling.

---

# Accessibility

Support keyboard navigation.

Maintain sufficient color contrast.

Use semantic HTML.

Interactive elements must have visible focus states.

Icons should include accessible labels where appropriate.

Accessibility is not optional.

---

# Performance

UI should feel responsive.

Avoid unnecessary re-renders.

Lazy load large resources when appropriate.

Optimize images.

Minimize layout shifts.

Prioritize perceived performance.

---

# Component Philosophy

Components should be:

Reusable

Composable

Predictable

Independent

Avoid creating one-off components when a reusable solution is possible.

---

# Consistency

Every page should feel like part of the same application.

Buttons

Inputs

Tables

Dialogs

Cards

Typography

Spacing

Colors

should remain visually consistent throughout the project.

---

# Error States

Errors should be informative.

Never expose internal exception details.

Explain:

What happened.

What the user can do next.

Provide recovery whenever possible.

---

# Mobile Experience

Although the primary platform is desktop, mobile usability remains important.

Customer POS should be fully usable on mobile devices.

Administrative interfaces should remain functional on tablets.

---

# Design Principles

When making UI decisions, always prioritize:

1. Simplicity
2. Readability
3. Consistency
4. Accessibility
5. Performance
6. Maintainability

Avoid adding visual elements unless they improve the user experience.

Every element on the screen should have a clear purpose.

Less is more.