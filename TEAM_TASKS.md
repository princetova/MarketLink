# MarketLink Team Tasks

Update this table before starting work and when handing a feature to review. Do not assign a person's name until the team has agreed. Status must be one of `NOT STARTED`, `IN PROGRESS`, `REVIEW`, or `DONE`.

| Screen / Feature | Assigned To | Branch | Main Files | Status | Notes |
| --- | --- | --- | --- | --- | --- |
| Foundation |  | `setup/project-foundation` | Project configuration and foundation docs | REVIEW | Batch 00 foundation prepared; awaiting team review. |
| Splash Screen |  | `feature/splash-screen` |  | NOT STARTED |  |
| Public Homepage |  | `feature/public-homepage` |  | NOT STARTED |  |
| Authentication |  | `feature/authentication` |  | NOT STARTED |  |
| Customer Marketplace |  | `feature/customer-marketplace` |  | NOT STARTED |  |
| Farmer Dashboard |  | `feature/farmer-dashboard` |  | NOT STARTED |  |
| Farmer Products |  | `feature/farmer-products` |  | NOT STARTED |  |
| Farmer Orders |  | `feature/farmer-orders` |  | NOT STARTED |  |
| Pickup Slots |  | `feature/pickup-slots` |  | NOT STARTED |  |
| Sales & Insights |  | `feature/sales-insights` |  | NOT STARTED |  |
| Reviews |  | `feature/reviews` |  | NOT STARTED |  |
| Notifications |  | `feature/notifications` |  | NOT STARTED |  |
| Maps |  | `feature/maps` |  | NOT STARTED |  |
| AI Assistant |  | `feature/ai-assistant` |  | NOT STARTED |  |
| Admin Dashboard |  | `feature/admin-dashboard` |  | NOT STARTED |  |
| Admin Farmer Approvals |  | `feature/admin-farmer-approvals` |  | NOT STARTED |  |
| Admin Markets |  | `feature/admin-markets` |  | NOT STARTED |  |
| Testing / QA |  | `feature/testing-qa` |  | NOT STARTED |  |

## Assignment template

Copy this row for new work:

```text
| Feature name | Team member | feature/short-name | Key folders/files | NOT STARTED | Dependencies or review notes |
```

The integration lead should preferably coordinate changes to `routes/web.php`, `resources/css/app.css`, `resources/js/app.js`, `resources/views/layouts/*`, shared components, and architecture documentation. Feature developers should work mainly within their assigned module and flag shared-file changes before merging.
