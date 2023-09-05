@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => '{{ config("app.url") }}' ])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
# Hello {{$user['name']}},

{{$admin['name']}} has been accepted the following tool request-

@component('mail::table')
| Tool       	| Serial Number| Product Number | Modality |
| :------------- |:-------------|:-------------| :--------|
| {{$tools['description']}} | {{$tools['serial_no']}} | {{$tools['product_no']}} | {{$modality}} |
@endcomponent

Request Date:<br/>
<b>{{$requestDate}}</b>

Delivery Date:<br/>
<b>{{$deliveryDate}}</b>

Pickup Type:<br/>
<b>{{$pickupType}}</b>

Pickup Location:<br/>
<b>{{$site->address}}, {{$site->city->name}}, {{$site->province->name}}</b>

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
