# MarketLink Development Roadmap

This is the personal development tracker for MarketLink. Work on one screen or feature at a time and update its status when work begins, enters review, or is completed.

Allowed statuses are `NOT STARTED`, `IN PROGRESS`, `REVIEW`, and `DONE`.

| Feature | Branch | Status | Notes |
| --- | --- | --- | --- |
| Foundation | `setup/project-foundation` | DONE | Laravel foundation and core technical documentation are established. |
| Splash Screen | `feature/splash-screen` | REVIEW | Implemented and validated; awaiting GitHub Desktop review. |
| Public Homepage | `feature/public-homepage` | NOT STARTED |  |
| Authentication | `feature/authentication` | NOT STARTED | Implement role-aware login and registration in focused steps. |
| Customer Marketplace | `feature/customer-marketplace` | NOT STARTED |  |
| Customer Orders | `feature/customer-orders` | NOT STARTED | Pickup-only ordering. |
| Farmer Dashboard | `feature/farmer-dashboard` | NOT STARTED |  |
| Farmer Products | `feature/farmer-products` | NOT STARTED | Product remains separate from Inventory. |
| Farmer Inventory | `feature/farmer-inventory` | NOT STARTED | Market-specific quantity, price, reservation, and availability. |
| Farmer Orders | `feature/farmer-orders` | NOT STARTED |  |
| Pickup Slots | `feature/pickup-slots` | NOT STARTED |  |
| Sales & Insights | `feature/sales-insights` | NOT STARTED |  |
| Reviews | `feature/reviews` | NOT STARTED | Reviews require a completed order. |
| Notifications | `feature/notifications` | NOT STARTED |  |
| Map & Locations | `feature/maps` | NOT STARTED | OpenStreetMap and Leaflet direction. |
| AI Assistant | `feature/ai-assistant` | NOT STARTED | AI access must go through `AIService`. |
| Admin Dashboard | `feature/admin-dashboard` | NOT STARTED |  |
| Farmer Approval | `feature/farmer-approval` | NOT STARTED | Uses the frozen farmer status model. |
| Market Management | `feature/market-management` | NOT STARTED |  |
| Reports | `feature/reports` | NOT STARTED |  |
| Testing | `feature/testing` | NOT STARTED | Expand automated and manual coverage alongside features. |
| Deployment | `chore/deployment` | NOT STARTED | Complete only after the application is production-ready. |

## Updating the roadmap

Set a feature to `IN PROGRESS` only when development begins. Use `REVIEW` after implementation and validation but before the user commits and merges it. Set it to `DONE` only after the completed feature is integrated into `main`. Do not mark planned functionality complete without implementation and verification evidence.
