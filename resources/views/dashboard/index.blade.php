<x-volt-app title="Dashboard">
    <x-slot name="actions">
        <x-volt-link-button
            :url="route('dashboard.create')"
            icon="plus"
            :label="__('laravolt::action.add')"
        />
    </x-slot>
    
    @livewire(\App\Http\Livewire\Table\DashboardTable::class)
</x-volt-app>