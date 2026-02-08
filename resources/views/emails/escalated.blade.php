@component('mail::message')
# Ticket Escalated

A support ticket has been escalated.

**Reference:** {{ ->reference }}
**Subject:** {{ ->subject }}
**Priority:** {{ ->priority->label() }}
@if()
**Reason:** {{  }}
@endif

Immediate attention is required.

@component('mail::button', ['url' => ])
View Ticket
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent
