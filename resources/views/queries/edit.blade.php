<x-volt-app title="Query">
    <x-volt-panel title="Edit Query" icon="pencil">
        {!! form()->bind($query)->put()->action(route('queries.update', $query->id))->id('form-query') !!}
            
        @include('queries._form')
            
        {!! form()->close() !!}
    </x-volt-panel>
</x-volt-app>