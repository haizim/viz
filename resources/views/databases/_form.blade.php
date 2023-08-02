            {!! form()->text('name')->label('Nama')->required() !!}
            {!! form()->select('type', $types)->label('Tipe Database')->required() !!}
            {!! form()->text('host')->label('Host')->required() !!}
            {!! form()->text('port', 3306)->label('Port')->required() !!}
            {!! form()->text('dbname')->label('Nama Database')->required() !!}
            {!! form()->text('user')->label('Username')->required() !!}
            {!! form()->text('password')->label('Password')->required() !!}
            {!! form()->textarea('keterangan')->label('Keterangan')->required() !!}
            {!! form()->action(form()->submit('Simpan'), form()->button('Batal')) !!}