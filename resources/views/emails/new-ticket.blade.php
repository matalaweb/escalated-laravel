@component('mail::message')
# New Support Ticket

A new support ticket has been created.

**Reference:** {{ ->reference }}
**Subject:** {{ ->subject }}
**Priority:** {{ ->priority->label() }}

{{ ->description }}

@component('mail::button', ['url' => ])
View Ticket
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent
