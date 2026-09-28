# Instructions for AI Coding Assistants

Before coding, read these files in order:

1. `PROJECT_GUIDE.md`
2. `ARCHITECTURE.md`
3. `UI_GUIDE.md`
4. `DATABASE_PLAN.md`
5. `TEAM_TASKS.md`

Then inspect the current branch, working tree, affected module, tests, and existing conventions. Do not assume documentation has authorized a feature outside the user's current batch.

## Mandatory rules

- Preserve the modular Laravel monolith architecture.
- Use PHP, Laravel, Blade, HTML5, CSS3, and vanilla JavaScript.
- Do not introduce React, Vue, Angular, Next.js, Nuxt, jQuery, another backend framework, or microservices.
- Reuse shared Blade components and current `--ml-*` design tokens.
- Keep Product and Inventory separate.
- Keep Farmer and Market many-to-many through `farmer_markets`.
- Build pickup workflows only. Do not create delivery, shipment, courier, or `DELIVERED` architecture.
- Do not create online payments, payment gateways, wallets, payouts, or bank settlement architecture.
- Respect `CUSTOMER`, `FARMER`, and `ADMIN` authorization on the server.
- Route external AI access through `AIService`; AI cannot bypass Laravel authorization or business rules.
- Preserve the official MarketLink logo exactly.
- Keep complete pages responsive and accessible, not only isolated components.
- Do not rewrite or reformat unrelated teammate files.
- Do not place secrets in code, documentation, fixtures, or tracked environment files.
- Do not commit, push, merge, rebase, reset, change remotes, delete branches, or create pull requests automatically.

## Working method

Keep controllers thin, validate with Form Requests, authorize with middleware/Policies, and place multi-step workflows in focused services. Add only the migrations, classes, and infrastructure required by the current feature. Use transactions for order and inventory consistency. Paginate unbounded lists and test role boundaries and business rules.

Before finishing, review the full diff, run relevant PHP tests and the frontend build, report all created/modified/deleted files, identify any unverified manual behavior, and leave changes uncommitted for GitHub Desktop review.
