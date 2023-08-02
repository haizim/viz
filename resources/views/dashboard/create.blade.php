<x-volt-app title="Dashboard">
    {{-- <x-volt-panel title="Tambah Dashboard" icon="plus"> --}}
        {!! form()->open()->post()->action(route('dashboard.store'))->id('form-dashboard') !!}
            
        @include('dashboard._form')
            
        {!! form()->close() !!}
    {{-- </x-volt-panel> --}}
</x-volt-app>