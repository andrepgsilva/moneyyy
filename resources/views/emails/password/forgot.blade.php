@component('mail::message')
# @lang('forgot.forgot_password')

@lang('forgot.hello'), {{ $username }}!

@lang('forgot.here_is_confirmation_code'): {{ $confirmationNumber }}

@lang('forgot.with_best_regards'),<br>
{{ config('app.name') }}
@endcomponent
