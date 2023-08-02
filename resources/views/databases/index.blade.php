<x-volt-app :title="__('Database')">
    <x-slot name="actions">
        <x-volt-link-button
            :url="route('databases.create')"
            icon="plus"
            :label="__('laravolt::action.add')"
        />
    </x-slot>
    
    @livewire(\App\Http\Livewire\Table\DatabasesTable::class)
</x-volt-app>