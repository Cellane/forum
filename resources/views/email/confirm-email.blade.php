@component('mail::message')
# One Last Step

We just need you to confirm your email address to prove that you’re a human. You get it, right? Cool.

@component('mail::button', ['url' => route('register.confirm', ['token' => $user->confirmation_token])])
Confirm Email
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
