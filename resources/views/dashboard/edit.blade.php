<x-volt-app title="Dashboard">
    {{-- <x-volt-panel title="Tambah Dashboard" icon="plus"> --}}
        {!! form()->bind($dashboard)->put()->action(route('dashboard.update', $dashboard->id)) !!}
            
        @include('dashboard._form')
            
        {!! form()->close() !!}
    {{-- </x-volt-panel> --}}
</x-volt-app>