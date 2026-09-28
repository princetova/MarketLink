# MarketLink Architecture

## Architecture decision

MarketLink is a modular Laravel monolith. One Laravel application owns HTTP delivery, authorization, business rules, persistence, background work, and integrations. Features remain separated by clear modules and services inside that application.

```text
Browser
  ↓
Laravel Routes
  ↓
Middleware
  ↓
Controllers / Form Requests
  ↓
Services
  ↓
Models / Eloquent
  ↓
MySQL
```

Controllers coordinate requests and responses. Form Requests validate input. Middleware and Policies enforce permissions. Services own multi-step business operations and transaction boundaries. Models represent persisted data and relationships.

## Frozen business boundaries

- Roles are `CUSTOMER`, `FARMER`, and `ADMIN`.
- **Product is not Inventory.** A Product describes what a farmer sells. Inventory records quantity, reserved stock, market, current price, availability, and availability period.
- **Farmer and Market are many-to-many.** The relationship uses `farmer_markets`.
- MarketLink is pickup only. There is no delivery, courier, shipment, or delivered status.
- MarketLink does not process online payments. Payment happens physically at pickup.
- Farmers require approval and will use `PENDING`, `APPROVED`, `SUSPENDED`, or `REJECTED` status.
- Orders will use `PENDING`, `ACCEPTED`, `PREPARING`, `READY`, `COMPLETED`, `CANCELLED`, or `DECLINED` status.
- Reviews require a completed order.
- AI is accessed through `AIService` and must pass through Laravel authorization and business rules.

## Module direction

Public routes remain in `routes/web.php`. Customer, farmer, and admin route groups are registered from their corresponding route files with stable prefixes and name prefixes. Feature controllers, requests, views, services, and policies should be grouped consistently by domain as the codebase grows.

Expected services include `InventoryService`, `OrderService`, `SearchService`, `MapService`, `AIService`, `NotificationService`, and `ReportService`. Add each only when a real feature needs it; do not create empty service classes.

## Supporting systems

- **Caching:** use Laravel's cache contracts. Keep cache keys scoped and invalidate them from the business operation that changes the data. Redis-ready configuration can be introduced when required.
- **Queues:** move slow or retryable work to Laravel Jobs and queues. Do not hide core order consistency behind an eventually processed job.
- **Events and listeners:** publish meaningful completed domain events, then attach secondary reactions such as notifications and audit work.
- **Notifications:** use Laravel Notifications for email and in-app channels. Persist user-visible notifications when the feature requires it.
- **Maps:** `MapService` will isolate OpenStreetMap/Leaflet integration and location transformations.
- **AI:** `AIService` will isolate the external LLM provider. The service receives authorized, minimum-necessary context and cannot directly alter protected data.
- **Storage:** use Laravel Storage for product images and documents so local storage can later move to object storage.

## Scale posture

The design should support 10,000+ registered users through indexed foreign keys and lookup columns, pagination, eager loading, bounded queries, cacheable reads, queued secondary work, and object storage. Add these measures with real features and measured needs; do not split the application into microservices or prebuild unused infrastructure.
