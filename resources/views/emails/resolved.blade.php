@component('mail::message')
# Ticket Resolved

Your support ticket has been resolved.

**Reference:** {{ ->reference }}
**Subject:** {{ ->subject }}

If you need further assistance, you can reopen this ticket.

@component('mail::button', ['url' => ])
View Ticket
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent
