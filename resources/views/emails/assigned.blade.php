@component('mail::message')
# Ticket Assigned to You

A support ticket has been assigned to you.

**Reference:** {{ ->reference }}
**Subject:** {{ ->subject }}
**Priority:** {{ ->priority->label() }}
**Status:** {{ ->status->label() }}

@component('mail::button', ['url' => ])
View Ticket
@endcomponent

Please review and respond at your earliest convenience.

Thank you,<br>
{{ config('app.name') }}
@endcomponent
