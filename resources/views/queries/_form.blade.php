{{-- <link href="/css/prism.css" rel="stylesheet" />
<script src="/js/codeflask.min.js"></script> 
<script src="/js/prism.js"></script>  --}}
<script src="/js/codemirror.js"></script> 
<link href="/css/codemirror.css" rel="stylesheet" />

<link href="/css/jquery.dataTables.min.css" rel="stylesheet" />
<script src="/js/jquery.dataTables.min.js"></script> 

{!! form()->text('name')->label('Nama')->required() !!}
{!! form()->dropdownDB('database_id', $databasesQuery, $keyColumn = 'id', $valueColumn = 'name')->label('Database')->id('database_id')->required() !!}

{!! form()->textarea('query')->label('Query')->id('query')->required() !!}
{{-- <textarea id="code"></textarea> --}}

<button type="button" class="ui primary basic button" id="run">Jalankan</button>

<table id="tableResult" class="display nowrap" style="width: 100%"></table>

{{-- @livewire('edit-query') --}}

{{-- <div class="field">
    <label>Query</label>
    <textarea id="code"></textarea>
</div> --}}

{!! form()->action(form()->button('Simpan')->id('simpan')->addClass('primary'), form()->button('Batal')) !!}

<script>
let dataSet = [["Belum ada data"]]
let cols = [{ 'title': 'Hasil' }]
var table

$.fn.dataTable.ext.classes.sPageButton = 'mini ui button';
$.fn.dataTable.ext.classes.sPageButtonActive = 'grey';

table = $('#tableResult').DataTable( {
    data: dataSet,
    columns: cols,
    searching: false,
    info: false,
    dom: 'rtip',
    ordering: false,
} )

$('#run').on('click', function () {
    table.destroy()
    $('#tableResult').empty()
    table = $('#tableResult').DataTable( {
        data: [['Menjalankan query']],
        columns: [{ 'title': '' }],
        searching: false,
        info: false,
        ordering: false,
        dom: 'rtip',
        scrollX: false,
    } );
    editor.save()
    query = editor.getTextArea().value
    console.log('query : ', query)

    $.post( "/api/run-query", {db: $('#database_id').val(), query: query} )
        .done(function( data ) {
            let dataSet = data.map((val) => (Object.values(val)))
            let columns = Object.keys(data[0])
            let cols = columns.map((val) => ({ 'title': val }))
            
            table.destroy()
            $('#tableResult').empty()
            table = $('#tableResult').DataTable( {
                data: dataSet,
                columns: cols,
                searching: false,
                info: false,
                ordering: false,
                "columnDefs": [
                    { "width": "20px", "targets": 0 }
                ],
                // pageLength: 5,
                // dom: 'rtip',
                scrollX: true
            } );
        })
        .fail(function(err) {
            let msg = err.responseJSON.message
            msg = msg.split(":")
            msg.shift()
            message = msg.join(":")
            message = message.replace("\n", "<br/>")
            message = "<b style='color: red'>" + message + "</b>"

            table.destroy()
            $('#tableResult').empty()
            table = $('#tableResult').DataTable( {
                data: [[message]],
                columns: [{ 'title': 'ERROR' }],
                searching: false,
                info: false,
                ordering: false,
                dom: 'rtip',
                scrollX: false,
            } );
        })
});

$('#simpan').on('click', function () {
    editor.save()
    $('#form-query').submit()
});

var editor = CodeMirror.fromTextArea(document.getElementById("query"), {
    lineNumbers: true
});
</script>