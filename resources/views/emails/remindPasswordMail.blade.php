@component('mail::layout')
{{-- Header --}}
@slot('header')
@component('mail::header', ['url' => config('app.url')])
    <!-- header here -->
@endcomponent
@endslot

{{-- Body --}}
# Hej {{ $user->name }},

Przejdź na poniższy link, żeby ustawić nowe hasło:

[{{ $link }}]({{ $link }}).
<!-- Body here -->

{{-- Subcopy --}}
@slot('subcopy')
@component('mail::subcopy')
    <!-- subcopy here -->
@endcomponent
@endslot


{{-- Footer --}}
@slot('footer')
@component('mail::footer')
    <!-- footer here -->
@endcomponent
@endslot
@endcomponent
