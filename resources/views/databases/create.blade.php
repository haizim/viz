<x-volt-app :title="__('Database')">
    <x-volt-panel title="{{ __('Tambah Database') }}" icon="plus">
        {!! form()->open()->post()->action(route('databases.store')) !!}
            
        @include('databases._form')
            
        {!! form()->close() !!}
    </x-volt-panel>
</x-volt-app>