<x-volt-base :title="$dashboard->name">
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
        @media (orientation:portrait){
            .item {
                width: 100% !important;
                max-width: 100% !important;
            }
        }
    </style>
    
    <header class="ui menu fixed top borderless" id="topbar">
        <div class="menu p-l-2" id="titlebar">
            <div class="left menu">
                <div class="item">
                    {{ config('laravolt.ui.brand_name') }}
                </div>
            </div>
        </div>
    </header>
    
    @include('laravolt::menu.actionbar', ['title' => $dashboard->name])

    <div class="layout--app">
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <div class="content">
            <div class="content__inner">
                <main class="ui container-fluid content__body p-3">
                    <x-volt-panel>
                        @livewire('show-dashboard', ["components" => $dashboard->components])
                    </x-volt-panel>
                </main>
            </div>
        </div>
    </div>
</x-volt-base>