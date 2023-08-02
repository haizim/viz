<x-volt-app :title="__('Database')">
    <x-volt-panel title="{{ __('Edit Database') }}" icon="pencil">
        {!! form()->bind($database)->put()->action(route('databases.update', $database->id)) !!}
            
        @include('databases._form')
            
        {!! form()->close() !!}
    </x-volt-panel>
</x-volt-app>