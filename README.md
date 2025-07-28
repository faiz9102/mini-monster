# Mini Monster Framework

A modular, Magento-inspired PHP framework for building scalable web applications. This project provides a robust foundation for developing both frontend and adminhtml (backend) interfaces, with a focus on extensibility, maintainability, and modern PHP best practices.

## Features

- **Dependency Injection Container**: Powerful DI system for managing class dependencies and service providers.
- **Custom Routing**: Flexible routing for both frontend and adminhtml controllers.
- **View & Layout System**: JSON-based layout definitions and block-based rendering, inspired by Magento 2.
- **Schema Validation**: Opis JSON Schema integration for validating data and layouts.
- **Service Providers**: Register and configure services (filesystem, logging, schema, view, etc.) via providers.
- **Error Handling**: Centralized error and exception handling with developer-friendly output.
- **Session & Cookie Management**: Built-in session and cookie managers.
- **Logging**: PSR-3 compatible logging with Monolog, logs stored in `var/log`.
- **Extensible**: Easily add new modules, controllers, blocks, and services.

## Directory Structure

```
app/                # Application code (controllers, models, services)
config/             # Configuration files (env, etc.)
bin/                # CLI scripts + REPL
framework/          # Core framework code (DI, View, Schema, etc.)
public/             # Web root (index.php, assets)
var/log/            # Application logs
vendor/             # Composer dependencies
view/               # Layouts and templates
```

## Getting Started

### Prerequisites
- PHP 8.1 or higher
- Composer
- Web server (Nginx/Apache) or PHP built-in server

### Installation
1. **Clone the repository:**
   ```bash
   git clone <your-repo-url> project_name
   cd project_name
   ```
2. **Install dependencies:**
   ```bash
   composer install
   ```
3. **Set up permissions:**
   ```bash
   chmod -R 755 var/cache var/log
   ```
4. **Configure your web server:**
   - Point your document root to `public/`
   
5. **Configure environment:**
   - Copy `config/env.php.sample` to `config/env.php` and adjust settings as needed.

## Usage

- **Frontend:** Visit `/` to access the frontend interface.
- **Adminhtml:** Visit `/{your configured admin route in env.php}` for the admin panel.
- **Add Controllers:** Place new controllers in `app/code/Controllers/` (see existing examples).
- **Layouts & Templates:**
  - Layouts: `view/layout/{area}/`
  - Templates: `view/templates/{area}/`

## Development
- **Service Providers:** Register new services in `app/code/Services/`
- **DI Container:** Bind interfaces and classes in `framework/DI/Container.php`
- **Logging:** Logs are written to `var/log/app.log` and daily log files.

## Extending the Framework
- Add new Controllers, Models, or Services by following the existing structure.
- Use the DI container for dependency management.
- Define new layouts and blocks for custom pages.

## Troubleshooting
- Check `var/log/` for error logs.
- Ensure permissions are set correctly for and `var/log`.
- Use xdebug or similar tools for proper debugging.

## License
This project is open-source and available under the MIT License.

---

**Happy coding!**

