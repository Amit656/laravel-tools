@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => '{{ config("app.url") }}' ])
            {{ config('app.name') }}
        @endcomponent
    @endslot

    {{-- Body --}}
# Hello {{$admin['name']}},

Following tool are due for calibiration on {{$calibrationDate}}

@component('mail::table')
| Tool       	| Serial Number| Product Number | Modality |
| :------------- |:-------------|:-------------| :--------|
| {{$tools['description']}} | {{$tools['serial_no']}} | {{$tools['product_no']}} | {{$tools->modality->name}} |
@endcomponent

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
