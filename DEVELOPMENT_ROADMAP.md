# MarketLink Development Roadmap

This is the personal development tracker for MarketLink. Work on one screen or feature at a time and update its status when work begins, enters review, or is completed.

Allowed statuses are `NOT STARTED`, `IN PROGRESS`, `REVIEW`, and `DONE`.

| Feature | Branch | Status | Notes |
| --- | --- | --- | --- |
| Foundation | `setup/project-foundation` | DONE | Laravel foundation and core technical documentation are established. |
| Splash Screen | `feature/splash-screen` | DONE | Implemented, validated, and integrated before the public homepage stage. |
| Public Homepage | `feature/public-homepage` | DONE | Implemented, validated, and integrated before the sign-in stage. |
| Sign In | `feature/login` | DONE | Session authentication and the sign-in screen are implemented, validated, and integrated. |
| Customer Registration | `feature/customer-registration` | DONE | Customer account creation is implemented, validated, and integrated. |
| Farmer Registration | `feature/farmer-registration` | DONE | Farmer account creation and pending approval state are implemented, validated, and integrated. |
| Farmer Account Status | `feature/farmer-account-status` | DONE | Protected status experience and role-aware authentication redirects are implemented, validated, and integrated. |
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
| Admin Farmer Approval | `feature/admin-farmer-approval` | REVIEW | Admin-only review workflow with search, filtering, pagination, controlled status transitions, and review metadata. |
| Market Management | `feature/market-management` | NOT STARTED |  |
| Reports | `feature/reports` | NOT STARTED |  |
| Testing | `feature/testing` | NOT STARTED | Expand automated and manual coverage alongside features. |
| Deployment | `chore/deployment` | NOT STARTED | Complete only after the application is production-ready. |

## Updating the roadmap

Set a feature to `IN PROGRESS` only when development begins. Use `REVIEW` after implementation and validation but before the user commits and merges it. Set it to `DONE` only after the completed feature is integrated into `main`. Do not mark planned functionality complete without implementation and verification evidence.
