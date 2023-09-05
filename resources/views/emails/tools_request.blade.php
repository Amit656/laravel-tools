@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => '{{ config("app.url") }}' ])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
# Hello {{$admin['name']}},

{{$user['name']}} have requested following tool-

@component('mail::table')
| Tool       	| Serial Number| Product Number | Modality |
| :------------- |:-------------|:-------------| :--------|
| {{$tools['description']}} | {{$tools['serial_no']}} | {{$tools['product_no']}} | {{$modality}} |
@endcomponent

Request Date:<br/>
<b>{{$requestDate}}</b>

Expected Return Date:<br/>
<b>{{$expectedReturnDate}}</b>

Pick Type:<br/>
<b>{{$pickupType}}</b>

Pickup Location:<br/>
<b>{{$tools['site']->address}}</b>

Drop Location:<br/>
<b>{{$dropLocation}}</b>

@slot('subcopy')
    @component('mail::subcopy')
Thanks & Regards,<br/>
{{ config('app.name') }}
    @endcomponent
@endslot

{{-- Footer --}}
@slot('footer')
    @component('mail::footer')
        © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
    @endcomponent
@endslot
@endcomponent
