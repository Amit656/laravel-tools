@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => '{{ config("app.url") }}' ])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
# Hello {{$user['name']}},

{{$admin['name']}} has been accepted the following tool return request-

@component('mail::table')
| Tool       	| Serial Number| Product Number | Modality |
| :------------- |:-------------|:-------------| :--------|
| {{$tools['description']}} | {{$tools['serial_no']}} | {{$tools['product_no']}} | {{$modality}} |
@endcomponent

Request Date:<br/>
<b>{{$requestDate}}</b>

Pickup Type:<br/>
<b>{{$pickupType}}</b>

Pickup Location:<br/>
<b>{{$tools['site']->address}}</b>

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
