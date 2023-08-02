<x-volt-app title="Query">
    <x-volt-panel title="Tambah Query" icon="plus">
        {!! form()->open()->post()->action(route('queries.store'))->id('form-query') !!}
            
        @include('queries._form')
            
        {!! form()->close() !!}
    </x-volt-panel>
</x-volt-app>