<x-volt-app title="Query">
    <x-slot name="actions">
        <x-volt-link-button
            :url="route('queries.create')"
            icon="plus"
            :label="__('laravolt::action.add')"
        />
    </x-slot>
    
    @livewire(\App\Http\Livewire\Table\QueriesTable::class)
</x-volt-app>