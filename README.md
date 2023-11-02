# Star Insure CRM Client

A package for Laravel apps that includes a wrapper for the Star Inure CRM and scaffolds out routes, controllers and middleware for authenticating with the Star auth app.

## Installation

You can install the package via composer:

```sh
composer require star-insure/crm-client
```

Add these values to your `.env` file:
```sh
# CRM
CRM_API_URL=http://crm.test
CRM_API_TOKEN=dev
CRM_API_GROUP_ID=2
CRM_API_VERSION=v1

```

### Publish config:
```sh
php artisan vendor:publish --tag=starinsure-crm
```

## Usage

### API
Call the Star CRM API by instantiating a new client, or using the `StarInsure\Api\Facades\CrmApi` facade.
```php
CrmApi::get('/account-manager-brokers');
```

### Helper functions

Create a `helpers.php` file within the `app` directory (or edit your existing one):
```php
if (! function_exists('crm')) {
    /**
     * Global helper to create an instance of the StarCrm client.
     */
    function crm()
    {
        return new \StarInsure\Crm\CrmApi(
            config('crm.version'),
        );
    }
}

```

Autoload your helpers file in `composer.json`:
```json
"autoload": {
    ...
    "files": [
        "app/helpers.php"
    ]
},
```

After adding the helpers file to composer.json, you'll need to dump the autoloader
```
composer dump-autoload
```

You can now use the global helper functions and not worry about namespaces/imports.
```php
crm()->get('/account-manager-brokers');
```
