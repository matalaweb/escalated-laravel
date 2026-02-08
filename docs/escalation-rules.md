# Escalation Rules

Escalation rules automatically take action on tickets that match specific conditions.

## Creating Rules

In the admin panel (`/support/admin/escalation-rules`), define rules with:

- **Name** — Rule identifier
- **Trigger Type** — `time_based` or `condition_based`
- **Conditions** — Array of conditions to match
- **Actions** — Array of actions to execute
- **Order** — Execution priority (lower = first)
- **Is Active** — Enable/disable the rule

## Available Conditions

| Field | Operator | Description |
|-------|----------|-------------|
| `status` | equals | Match specific status |
| `priority` | equals | Match specific priority |
| `assigned` | equals | `'unassigned'` or `'assigned'` |
| `age_hours` | greater_than | Hours since ticket created |
| `no_response_hours` | greater_than | Hours with no first response |
| `sla_breached` | equals | SLA has been breached |
| `department_id` | equals | Match specific department |

## Available Actions

| Action | Value | Description |
|--------|-------|-------------|
| `escalate` | — | Change status to Escalated |
| `change_priority` | priority value | Change ticket priority |
| `assign_to` | agent ID | Assign to specific agent |
| `change_department` | department ID | Move to different department |

## Example Rule

Auto-escalate unresponded tickets after 4 hours:

```json
{
    "conditions": [
        {"field": "status", "value": "open"},
        {"field": "no_response_hours", "value": 4}
    ],
    "actions": [
        {"type": "change_priority", "value": "high"},
        {"type": "escalate"}
    ]
}
```

## Scheduling

Add to your `app/Console/Kernel.php`:

```php
$schedule->command('escalated:evaluate-escalations')->everyFiveMinutes();
```
