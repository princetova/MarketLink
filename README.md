# MarketLink

MarketLink is a pickup-only agricultural marketplace that connects local farmers with customers through trusted physical markets. Customers discover produce and place pickup orders; farmers manage products, inventory, and orders; administrators approve farmers and oversee the platform.

## Technology stack

- PHP 8.2+ and Laravel 12
- Laravel Blade, HTML5, CSS3, and vanilla JavaScript
- MySQL
- Vite for frontend asset builds

## Requirements

- PHP 8.2 or newer with Laravel's required extensions and `pdo_mysql`
- Composer 2
- MySQL 8 or a compatible supported version
- Node.js 20+ and npm

## Installation

```bash
git clone https://github.com/princetova/MarketLink.git
cd MarketLink
composer install
npm install
cp .env.example .env
php artisan key:generate
```

On Windows Command Prompt, use `copy .env.example .env`. Create a local MySQL database named `marketlink` (or choose another name), then update the `DB_*` values in `.env`. Never commit `.env` or credentials.

When the project is ready to apply migrations:

```bash
php artisan migrate
```

Build assets and run the application:

```bash
npm run build
php artisan serve
```

For frontend development, use `npm run dev` in a separate terminal.

## Development workflow

Read [PROJECT_GUIDE.md](PROJECT_GUIDE.md), [ARCHITECTURE.md](ARCHITECTURE.md), [UI_GUIDE.md](UI_GUIDE.md), [DATABASE_PLAN.md](DATABASE_PLAN.md), [DEVELOPMENT_ROADMAP.md](DEVELOPMENT_ROADMAP.md), and [AI_INSTRUCTIONS.md](AI_INSTRUCTIONS.md) before starting feature work. Develop one screen or feature per branch, test and review it, then use GitHub Desktop to commit, push, and merge it into `main` before beginning the next feature.

MarketLink is licensed under the MIT License. See [LICENSE](LICENSE).
