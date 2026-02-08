@component('mail::message')
# New Reply on {{ ->reference }}

A new reply has been added to your support ticket.

**Subject:** {{ ->subject }}

---

{{ ->body }}

---

@component('mail::button', ['url' => ])
View Ticket
@endcomponent

Thank you,<br>
{{ config('app.name') }}
@endcomponent
