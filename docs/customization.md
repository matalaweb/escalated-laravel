# Customization

## Publishing Assets

### Customer UI Only
```bash
php artisan vendor:publish --tag=escalated-client-assets
```

### Agent & Admin UI
```bash
php artisan vendor:publish --tag=escalated-admin-assets
```

### Email Templates
```bash
php artisan vendor:publish --tag=escalated-views
```

### Configuration
```bash
php artisan vendor:publish --tag=escalated-config
```

## Custom User Model

Set your user model in config:

```php
'user_model' => App\Models\User::class,
```

Your model must implement `Ticketable` and use `HasTickets`:

```php
use Escalated\Laravel\Contracts\HasTickets;
use Escalated\Laravel\Contracts\Ticketable;

class User extends Authenticatable implements Ticketable
{
    use HasTickets;
}
```

## Custom Authorization

Define gates in your service provider:

```php
Gate::define('escalated-admin', function ($user) {
    return $user->hasRole('admin');
});

Gate::define('escalated-agent', function ($user) {
    return $user->hasRole('agent') || $user->hasRole('admin');
});
```

## Table Prefix

Change the database table prefix:

```php
'table_prefix' => 'support_', // Default: 'escalated_'
```

## Custom Notification Channels

Override which channels notifications use:

```php
'notifications' => [
    'channels' => ['mail', 'database', 'slack'],
],
```

## Webhooks

Send events to an external URL:

```php
'notifications' => [
    'webhook_url' => 'https://your-app.com/webhooks/escalated',
],
```

Webhook payload:
```json
{
    "event": "ticket.created",
    "payload": { ... },
    "timestamp": "2026-02-07T12:00:00Z"
}
```

## Custom Status Transitions

Control which statuses can transition to which:

```php
'transitions' => [
    'open' => ['in_progress', 'closed'],
    'in_progress' => ['resolved'],
    'resolved' => ['closed', 'reopened'],
],
```
