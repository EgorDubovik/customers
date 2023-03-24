@component('mail::layout')
    {{-- Header --}}
    @slot('header')
        @component('mail::header', ['url' => config('app.url')])
            
        @endcomponent
    @endslot

    {{-- Body --}}
    <!-- Body here -->
    Dear {{ $invoice->customer_name}},

    I hope you're well. 
    Please see attached invoice. 
    Don't hesitate to reach out if you have any questions.

    Thanks 
    {{-- Footer --}}
    @slot('footer')
        @component('mail::footer')
            {{ date('m-d-Y') }} - {{ env('APP_NAME') }}
        @endcomponent
    @endslot
@endcomponent