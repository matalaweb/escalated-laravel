@component('mail::message')
# Ticket Status Updated

The status of your support ticket has been updated.

**Reference:** {{ ->reference }}
**Subject:** {{ ->subject }}
**Previous Status:** {{ ->label() }}
**New Status:** {{ ->label() }}

@component('mail::button', ['url' => ])
View Ticket
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent
