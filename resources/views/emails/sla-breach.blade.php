@component('mail::message')
# SLA Breach Alert

An SLA has been breached on a support ticket.

**Reference:** {{ ->reference }}
**Subject:** {{ ->subject }}
**Priority:** {{ ->priority->label() }}
**Breach Type:** {{  === 'first_response' ? 'First Response' : 'Resolution' }}

Immediate attention is required.

@component('mail::button', ['url' => ])
View Ticket
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent
