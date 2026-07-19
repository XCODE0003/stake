{{-- $appName lets a per-brand email override the global config('app.name') in
     the header, footer and document title. Other emails omit it and fall back. --}}
@props(['appName' => null])
<x-mail::layout :appName="$appName">
{{-- Header --}}
<x-slot:header>
<x-mail::header :url="config('app.url')">
{{ $appName ?? config('app.name') }}
</x-mail::header>
</x-slot:header>

{{-- Body --}}
{!! $slot !!}

{{-- Subcopy --}}
@isset($subcopy)
<x-slot:subcopy>
<x-mail::subcopy>
{!! $subcopy !!}
</x-mail::subcopy>
</x-slot:subcopy>
@endisset

{{-- Footer --}}
<x-slot:footer>
<x-mail::footer>
© {{ date('Y') }} {{ $appName ?? config('app.name') }}. {{ __('All rights reserved.') }}
</x-mail::footer>
</x-slot:footer>
</x-mail::layout>
