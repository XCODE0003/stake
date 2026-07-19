{{--
    Verification-code email, branded to the site the affiliate registered on.
    $appName drives the header/footer/title (see vendor/mail components); the
    body and salutation use $brandName so nothing says "Stake" on other sites.
--}}
<x-mail::message :appName="$brandName">
# {{ __('Confirm your email') }}

{{ __('Your :brand verification code is:', ['brand' => $brandName]) }}

# {{ $code }}

{{ __('The code is valid for :minutes minutes.', ['minutes' => $ttl]) }}

{{ __('If you did not register, you can safely ignore this email.') }}

{{ __('Thanks,') }}<br>
{{ $brandName }}
</x-mail::message>
