<x-volt-app title="Dashboard">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .baris{
            padding: 0 5%;
            transition: all 1s;
        }
        .baris>.tambah{
            margin-left: -2.7%;
        }
        .tambah {
            cursor: pointer;
            font-weight: bold;
            text-align: center;
            padding: .5em;
            border-radius: 1em;
            color: #00b5ad;
            background-color: #00b5ad58;
            width: max-content;
            transition: all .5s;
            font-size: x-small
        }
        .tambah.down{
            margin-bottom: -2.3em;
            z-index: 9;
            position: relative;
        }
        .tambah .x-icon:hover {
            color: #002423;
        }
        .editor * {
            transition: .5s all;
        }
    </style>
    
    <x-volt-panel title="Lihat Dashboard" icon="eye">
        @livewire('show-dashboard', ["components" => $dashboard->components])
    </x-volt-panel>
</x-volt-app>