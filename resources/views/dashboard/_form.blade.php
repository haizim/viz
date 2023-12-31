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
    @media (orientation:portrait){
        .item {
            width: 100% !important;
            max-width: 100% !important;
        }
    }
</style>

{!! form()->text('name')->label('Nama')->required() !!}

@if (isset($dashboard))
@livewire('edit-dashboard', ["components" => $dashboard->components])
@else
@livewire('edit-dashboard')
@endif

{!! form()->action(form()->submit('Simpan')->id('simpan')->addClass('primary'), form()->button('Batal')) !!}