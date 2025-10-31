# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

This is a **Soccer Club Licensing Management System** built with Laravel 12 and Livewire 3. The application manages the licensing process for football clubs, including document submission, multi-stage review workflows, and approval processes. It supports three languages (Russian, Kazakh, English) and includes a dark theme with Tailwind CSS.

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Livewire 3 with Blade templates
- **Styling**: Tailwind CSS 4 with dark mode support
- **Build Tool**: Vite
- **Database**: SQLite (default), supports MySQL/PostgreSQL
- **Queue**: Database driver
- **Cache**: Database driver

## Common Development Commands

### Setup & Installation
```bash
composer setup              # Full setup: install dependencies, generate key, migrate DB, build assets
```

### Development Server
```bash
composer dev               # Run all dev services (server, queue, logs, vite) concurrently
php artisan serve          # Run only the development server (localhost:8000)
npm run dev                # Run Vite dev server for hot module replacement
```

### Database Operations
```bash
php artisan migrate        # Run migrations
php artisan migrate:fresh --seed  # Reset database and seed
php artisan db:seed        # Seed database
```

### Testing
```bash
composer test              # Run PHPUnit tests
php artisan test           # Alternative way to run tests
```

### Code Quality
```bash
./vendor/bin/pint          # Run Laravel Pint (code formatter)
```

### Queue Management
```bash
php artisan queue:work      # Process queued jobs
php artisan queue:listen    # Listen for new jobs (auto-reload on code changes)
```

### Logs
```bash
php artisan pail            # Tail application logs in real-time
```

### Asset Compilation
```bash
npm run build              # Build production assets
npm run dev                # Run Vite dev server
```

## Architecture & Structure

### Role-Based Access Control

The application implements a complex RBAC system with three main user categories:

1. **Admin**: System administrator with full access
2. **Club Roles**: Club-side users (Club Administrator, Legal Specialist, Financial Specialist, Sporting Director)
3. **Department Roles**: Federation-side reviewers (Licensing Department, Legal Department, Finance Department, Infrastructure Department, Control Department)

Role constants are defined in `app/Constants/RoleConstants.php` with both IDs and slug values.

### Layout System

The application uses a role-based layout system managed by the `get_user_layout()` helper function in `app/Helpers/LayoutHelper.php`:

- **Admin**: `layouts.admin`
- **Club Roles**: `layouts.club`
- **Department Roles**: `layouts.department`
- **Guest**: `layouts.guest`

**Always use** `get_user_layout()` in Livewire component render methods to ensure correct layout routing.

### Application Licensing Workflow

The core business logic revolves around **Applications** that go through a multi-stage approval process:

1. **Document Submission** (by clubs)
2. **First Check** (licensing department)
3. **Industry-Specific Checks** (legal, finance, infrastructure departments)
4. **Control Check** (control department)
5. **Final Decision** (approval/rejection)

Application statuses are defined in `app/Constants/ApplicationStatusConstants.php` with 12 distinct states including revision loops.

### Multilingual Support

**Critical Convention**: All user-facing text fields must support three languages:

- **Database columns**: `field_ru`, `field_kk`, `field_en` (snake_case)
- **Component properties**: `fieldRu`, `fieldKk`, `fieldEn` (camelCase)
- **Required fields**: Validate all three language variants
- **Display**: Show all languages in tables/cards (primary + secondary text)

Example validation:
```php
#[Validate('required|string|max:255')]
public $titleRu = '';

#[Validate('required|string|max:255')]
public $titleKk = '';

#[Validate('required|string|max:255')]
public $titleEn = '';
```

### Livewire Component Structure

All Livewire components follow a strict standardized pattern documented in `COMPONENT_GENERATION_PROMPT.md`. Key patterns:

#### Property Organization
1. Modal states (`showCreateModal`, `showEditModal`)
2. Search & filters (`search`, `filterField`)
3. Form data with `#[Validate]` attributes
4. Relationship data
5. Permissions with `#[Locked]` attribute

#### Authorization Pattern
```php
public function mount()
{
    $this->authorize('view-permission');

    $user = auth()->user();
    $this->canCreate = $user->can('create-permission');
    $this->canEdit = $user->can('manage-permission');
    $this->canDelete = $user->can('delete-permission');
}
```

Permission naming: `{action}-{entity-plural}` (e.g., `view-clubs`, `create-clubs`, `manage-clubs`, `delete-clubs`)

#### Search & Filtering Pattern
- Always implement `updatedSearch()` and `updatedFilter*()` methods to reset pagination
- Search across all language variants in queries
- Use `wire:model.live.debounce.500ms` for search inputs

### Dark Mode Implementation

**Every UI element must support dark mode**. Standard class patterns:

- Backgrounds: `bg-white dark:bg-gray-800`
- Text: `text-gray-900 dark:text-gray-100`
- Borders: `border-gray-200 dark:border-gray-700`
- Inputs: `border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-100`
- Focus: `focus:ring-blue-500 dark:focus:ring-blue-400`

Status badge gradients are defined in `COMPONENT_GENERATION_PROMPT.md` lines 629-649.

### Constants Pattern

All domain constants are organized in `app/Constants/` directory:
- `RoleConstants.php`: User roles
- `ApplicationStatusConstants.php`: Application workflow states
- `ApplicationStatusCategoryConstants.php`: Status categories
- `CategoryDocumentConstants.php`: Document categories
- `ClubTypeConstants.php`: Club types
- `LeagueConstants.php`: League types
- `FileExtensionConstants.php`: Allowed file extensions

Each constant class defines both IDs and slug values (e.g., `ADMIN_ROLE_ID = 1`, `ADMIN_ROLE_VALUE = 'admin'`).

### Model Relationships

Key models and relationships:

- **Application**: Central entity linking User, Club, Licence, and ApplicationStatusCategory
  - Has many: ApplicationCriterion, ApplicationReport, ApplicationSolution, ApplicationStep
  - Belongs to many: Documents (through pivot with extensive metadata)

- **User**: Belongs to Role, Club (for club users)
- **Club**: Has many Applications, ClubTeams; belongs to ClubType, League
- **Licence**: Has many LicenceRequirements, LicenceDeadlines

Models are auto-generated using `reliese/laravel` package (see `composer.json`).

### Component Generation

When creating new Livewire components, follow the comprehensive guide in `COMPONENT_GENERATION_PROMPT.md`. It defines:

- Four component types (Simple CRUD, Complex Entity, Card Workflow, Detail View)
- Complete code templates for all scenarios
- Blade view patterns with dark mode
- Validation patterns
- Modal structures
- Common mistakes to avoid

## Project-Specific Conventions

### Autoloaded Helpers

`app/Helpers/LayoutHelper.php` is autoloaded via `composer.json` and provides:
- `get_user_layout()`: Returns appropriate layout based on user role
- `get_role_icon($roleValue)`: Returns FontAwesome icon for role
- `get_role_color($roleValue)`: Returns Tailwind gradient classes for role badges

### Middleware

- `auth`: Standard Laravel authentication
- `active`: Custom middleware (`CheckUserActive`) that verifies user is active

### Icons

FontAwesome 6 is used throughout. Common icons defined in `COMPONENT_GENERATION_PROMPT.md` lines 788-800.

### Flash Messages

Standard patterns for success/error messages in Blade views (lines 311-330 in `COMPONENT_GENERATION_PROMPT.md`).

## Development Workflow

1. **Models**: Auto-generated with Reliese (`php artisan code:models`)
2. **Migrations**: Create migrations for schema changes
3. **Livewire Components**: Use CRUD patterns from `COMPONENT_GENERATION_PROMPT.md`
4. **Permissions**: Define in `app/Constants/` and seed via database
5. **Routes**: Most are Livewire components, minimal web routes in `routes/web.php`

## Important Notes

- **Never skip authorization checks** in Livewire methods
- **Always validate all three languages** for required text fields
- **Always include dark mode classes** in all UI elements
- **Use `get_user_layout()`** in all Livewire render methods
- **Reset pagination** when search/filter changes (implement `updated*()` methods)
- **Clean up media** when deleting entities (if using Spatie Media Library)
- **Use FontAwesome icons** consistently for better UX
- **Follow naming conventions**: camelCase for properties, snake_case for database columns
