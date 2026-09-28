# MarketLink Project Guide

## Product purpose

MarketLink is a scalable agricultural marketplace connecting local farmers with customers. It supports discovery and pickup at physical markets. It does not provide delivery or process online payments.

The three application roles are:

- **Customer:** browses available produce, places pickup orders, and reviews completed orders.
- **Farmer:** operates at one or more markets and manages products, inventory, pickup preparation, and orders after approval.
- **Admin:** approves farmers and manages platform data, markets, announcements, and oversight.

## Locked technology

- Backend: PHP and Laravel
- Frontend: Laravel Blade, HTML5, CSS3, and vanilla JavaScript
- Database: MySQL
- Architecture: modular Laravel monolith

Do not introduce React, Vue, Angular, Next.js, Nuxt, jQuery, another backend framework, or microservices without an explicit architecture decision approved by the team.

## Local setup

1. Clone the repository and enter its root.
2. Run `composer install`.
3. Run `npm install`.
4. Copy `.env.example` to `.env`.
5. Run `php artisan key:generate`.
6. Create a local MySQL database and configure the `DB_*` values in `.env`.
7. Run `php artisan migrate` when migrations for the current batch are ready.
8. Run `npm run build` and `php artisan serve`.

Local credentials belong only in `.env`. Never place passwords, database credentials, API keys, email credentials, or production secrets in tracked files.

## Folder organization

- `app/Http/Controllers`: thin HTTP coordination.
- `app/Http/Requests`: validation and request authorization.
- `app/Http/Middleware`: request pipeline and role/access concerns.
- `app/Models`: Eloquent models and relationships.
- `app/Services`: reusable business workflows such as inventory and order operations.
- `app/Policies`: model-level authorization.
- `app/Events`, `app/Listeners`, `app/Jobs`, `app/Notifications`: event-driven and asynchronous behavior as features require it.
- `resources/views`: Blade views grouped into `layouts`, `components`, `public`, `auth`, `customer`, `farmer`, `admin`, and `system`.
- `routes/web.php`: public browser routes.
- `routes/customer.php`, `routes/farmer.php`, `routes/admin.php`: role-specific browser routes.

Create classes and files only when a feature needs them. Do not add fake empty classes to fill the structure.

## Coding rules

- Keep controllers small; place multi-step business logic in a focused service.
- Use Form Request classes for non-trivial validation and Policies or middleware for authorization.
- Keep Product and Inventory as separate concepts.
- Model Farmer and Market as many-to-many through `farmer_markets`.
- Use pickup language and workflows only; never add delivery states or courier logic.
- Orders are paid physically at pickup; never add payment gateway or wallet logic.
- Keep routes named, views responsive, database access through Eloquent/query builder, and list screens paginated.
- Prefer existing shared components and design tokens over duplication.
- Add or update tests with feature work.
- Do not rewrite unrelated teammate files.

## Git workflow

GitHub Desktop is the team tool for creating branches, committing, pulling, pushing, merging, and resolving Git operations. Before coding, pull the latest approved base branch and create a focused branch. One feature should generally be developed on one feature branch.

Branch patterns:

- `setup/<name>` for project setup
- `feature/<name>` for features
- `fix/<name>` for corrections
- `docs/<name>` for documentation

Examples: `feature/splash-screen`, `feature/public-homepage`, `feature/farmer-products`, `feature/customer-orders`, and `feature/admin-markets`.

Before handing off work:

1. Review the diff in GitHub Desktop.
2. Run relevant tests and `npm run build`.
3. Confirm secrets and local artifacts are not included.
4. Record the feature, branch, status, and notes in `TEAM_TASKS.md`.
5. Ask the integration lead to review shared-file changes.

The integration lead should preferably coordinate `routes/web.php`, `resources/css/app.css`, `resources/js/app.js`, `resources/views/layouts/*`, shared components, and architecture documentation. Feature developers should mainly edit their assigned module.
