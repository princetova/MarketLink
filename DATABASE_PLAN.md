# MarketLink Database Plan

This document records the planned logical model. It is not a request to generate all migrations at once. Each feature should introduce only the tables, constraints, and indexes it owns.

## Identity

- `users`: shared credentials, role, account state, and verified contact identity.
- `customer_profiles`: customer-specific profile data; belongs to a user.
- `farmer_profiles`: farmer identity, business information, and approval status; belongs to a user.

Use one authenticated user identity. Customer and farmer profile tables extend it without duplicating authentication credentials. Admin access is represented through the user's authorized role rather than a separate login system.

## Marketplace

- `markets`: physical pickup markets, location data, and operating state.
- `farmer_markets`: pivot joining farmers to the markets where they operate; unique per farmer/market pair.
- `categories`: product classification.
- `products`: a farmer's reusable product description, category, and descriptive attributes.
- `product_images`: ordered storage references belonging to a product.
- `inventories`: a product's market-specific quantity, reserved stock, current price, availability state, and availability period.

Product and Inventory are separate. A product can exist before it has stock and can have distinct inventory records for different markets or availability periods. Inventory must not allow reserved stock to exceed available stock.

## Orders

- `orders`: customer, pickup market/slot, owning farmer context, totals, status, and timestamps.
- `order_items`: immutable order-time product description, quantity, unit price, and line total.
- `order_status_history`: append-only status transitions, actor, timestamp, and optional reason.
- `pickup_slots`: farmer/market pickup windows and capacity or availability rules.

Order statuses are `PENDING`, `ACCEPTED`, `PREPARING`, `READY`, `COMPLETED`, `CANCELLED`, and `DECLINED`. There is no `DELIVERED` state. Online payment, wallet, payout, shipment, courier, and delivery tables are outside the product scope.

## Engagement

- `favorites`: unique customer/product saved relationship.
- `reviews`: rating and text tied to a completed order or eligible order item.
- `notifications`: persisted in-app notification payload and read state when required.

Review eligibility must be verified server-side from a completed order. Do not rely on a client-provided eligibility flag.

## AI

- `chat_conversations`: user-owned conversation metadata and role context.
- `chat_messages`: ordered user/assistant messages belonging to a conversation.

Store only necessary conversation data. AI access goes through `AIService`, authorization, validation, and audited Laravel business operations.

## Platform

- `announcements`: time-bounded platform messages and target audience.
- `audit_logs`: significant administrative and business actions with actor and subject references.
- `system_settings`: validated, typed platform settings; never secret credentials.

## Relationship summary

- User has zero or one Customer Profile and zero or one Farmer Profile as allowed by role policy.
- Farmer Profile belongs to many Markets through `farmer_markets`; Market belongs to many Farmer Profiles.
- Farmer owns many Products; Category has many Products.
- Product has many Product Images and many Inventories.
- Inventory belongs to a Product and Market.
- Customer places many Orders; an Order has many Order Items and status-history entries.
- Pickup Slot belongs to the relevant Market and farmer operating context.
- Customer favorites Products and may review only eligible completed purchases.
- Conversation belongs to a User and has many ordered Chat Messages.

## Data integrity and performance

Use foreign keys, unique constraints, appropriate decimal types for money, timestamps, and explicit status validation. Add indexes for foreign keys and real filter/sort patterns. Use database transactions for stock reservation and order transitions. Avoid storing computed totals without defining how they are atomically maintained. Paginate growing lists and prevent N+1 queries with deliberate eager loading.
