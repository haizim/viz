{!! form()->text('name')->label('Nama')->required() !!}
{!! form()->select('type', $types)->id('type')->label('Tipe Database')->required() !!}
{!! form()->text('host')->id('host')->label('Host')->required() !!}
{!! form()->text('port', 3306)->id('port')->label('Port')->required() !!}
{!! form()->text('dbname')->id('dbname')->label('Nama Database')->required() !!}
{!! form()->text('user')->id('user')->label('Username')->required() !!}
{!! form()->text('password')->id('password')->label('Password')->required() !!}
{!! form()->textarea('keterangan')->label('Keterangan')->required() !!}

<button type="button" class="ui inverted primary button" id="test-btn">Test Connection</button>
<a class="ui large grey empty circular label" id="test-indicator"></a>
<b id="test-label">Not Conneccted</b>

{!! form()->action(form()->submit('Simpan'), form()->button('Batal')) !!}

@push('script')
<script>
    let value
    $("#test-btn").on( "click", function() {
        value = {
            type: $('#type').val(),
            host: $('#host').val(),
            port: $('#port').val(),
            dbname: $('#dbname').val(),
            user: $('#user').val(),
            password: $('#password').val(),
        }
        
        $.post( "/api/test-connection", value )
        .done(function( data ) {
            $('#test-label').text('OK')
            $('#test-indicator').attr("class", "ui large green empty circular label")
        })
        .fail(function(err) {
            $('#test-label').text('Failed')
            $('#test-indicator').attr("class", "ui large red empty circular label")
        })
    } );
</script>
@endpush