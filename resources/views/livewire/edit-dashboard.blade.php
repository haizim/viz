<div class="editor">
    <link href="/css/jquery.dataTables.min.css" rel="stylesheet" />
    <script src="/js/jquery.dataTables.min.js"></script>
    
    <x-volt-panel title="Tambah Dashboard" icon="plus">
        <script>
            let datas = []
            const pluck = (arr, key) => arr.map(i => i[key]);
            
            function transformDataset(type, data, keys=[])
            {
                let result = []
                let key = ""
                
                switch (type) {
                    case "line":
                    for (let i = 1; i < keys.length; i++) {
                        key = keys[i]
                        result[i-1] = {}
                        result[i-1]['label'] = key
                        result[i-1]['data'] = pluck(data, key)
                    }
                    if (typeof data[0][keys[0]] != "number") {
                        result = pivotDataLine(result)
                    }
                    break;

                    case "table":
                        let r = 0
                        let c = 1
                        data.forEach(i => {
                            c = 1
                            result[r] = []
                            result[r][0] = r+1
                            Object.values(i).forEach(j => {
                                result[r][c] = j
                                c++
                            });
                            r++
                        });
                    break;
                    
                    default:
                    for (let i = 1; i < keys.length; i++) {
                        key = keys[i]
                        result[i-1] = {}
                        result[i-1]['label'] = key
                        result[i-1]['data'] = pluck(data, key)
                    }
                    break;
                }
                
                return result
            }
            
            function pivotDataLine(data)
            {
                result = {}
                index = data[0]['data']
                n = 0
                
                index.forEach(e => {
                    result[e] = []
                })
                
                for (let i = 1; i < data.length; i++) {
                    e = data[i]
                    for (let j = 0; j < index.length; j++) {
                        elem = e['data'][j];
                        indexNow = index[j]
                        result[indexNow].push(elem)
                    }
                }
                dataset = []
                
                for (const [key, value] of Object.entries(result)) {
                    ds = {
                        "label" : key,
                        "yAxis" : key,
                        "data" : value,
                    }
                    dataset.push(ds)
                }
                
                return dataset
            }
        </script>
        <div>
            @php
            $dehydrateThis = "";    
            @endphp
            
            @foreach ($components as $r => $row)
            <div class="tambah down">
                <span wire:click="movePrevRow({{ $r }})">
                    <x-volt-icon name="arrow-up"/>
                </span>|
                <span wire:click="moveNextRow({{ $r }})">
                    <x-volt-icon name="arrow-down"/>
                </span>|
                <span wire:click="addCol({{ $r }})">
                    <x-volt-icon name="plus"/>
                </span>|
                <span wire:click="delRow({{ $r }})">
                    <x-volt-icon name="trash"/>
                </span>|
                <span>
                    <x-volt-icon name="arrows-v"/>
                    <input type="text" style="width: 5em; height: 3em;" wire:model="components.{{ $r }}.height">
                </span>
            </div>
            
            <script>
                datas[{{ $r }}] = [];
            </script>
            
            <div class="ui stackable equal width grid baris">
                @forelse ($row['items'] as $i => $item)
                
                @php
                $r_i = $r."_$i";
                $rai = "[".$r."][$i"."]";
                $rsi = $r."-$i";
                $rii = $r.'.items.'.$i;
                $width = $item['width'] ?? "25";
                $display = [];
                $hide = "display: none;";
                
                switch ($item['type']) {
                    case 'text':
                    $display = [$hide, "", $hide];
                    break;
                    case 'table':
                    $display = [$hide, $hide, ""];
                    break;
                    
                    default:
                    $display = ["", $hide, $hide];
                    break;
                }
                
                $dehydrateThis .= "visualize_$r_i();\n";
                @endphp
                
                <div class="column p-1 item" style="max-width:{{ $width }}%">
                    <div class="ui card" style="width: 100%">
                        
                        <div class="content">
                            <div class="header">
                                <h4 id="header-{{ $rsi }}"></h4>
                            </div>
                        </div>
                        
                        <div class="content">
                            <div class="fields editor-item">
                                <div class="field">
                                    <select
                                    class="ui search dropdown"
                                    wire:model="components.{{ $rii }}.type"
                                    id="type-{{ $rsi }}"
                                    >
                                    <option value="">Select Chart</option>
                                    @foreach ($chartTypes as $ct)
                                    <option value="{{ $ct }}">{{ $ct }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="field">
                                <select
                                class="ui search dropdown"
                                wire:model="components.{{ $rii }}.query"
                                id="query-{{ $rsi }}"
                                >
                                <option value="0">Select Query</option>
                                @foreach ($queries as $q)
                                <option value="{{ $q['id'] }}">{{ $q['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="field">
                            <input
                            type="text"
                            id="width-{{ $rsi }}"
                            wire:model="components.{{ $rii }}.width"
                            placeholder="width(%)"
                            >
                        </div>
                    </div>
                    <center>
                        <div class="tambah">
                            <span wire:click="movePrevCol({{ $r }}, {{ $i }})">
                                <x-volt-icon name="arrow-left"/>
                            </span>
                            <span wire:click="delCol({{ $r }}, {{ $i }})">
                                <x-volt-icon name="trash"/>
                            </span>
                            <span wire:click="moveNextCol({{ $r }}, {{ $i }})">
                                <x-volt-icon name="arrow-right"/>
                            </span>
                        </div>
                    </center>
                    <div class="chart-container" style="height:{{ $row['height'] }}">
                        <canvas id="chart-{{ $rsi }}"  style="{{ $display[0] }}"></canvas>
                        <center>
                            <div class="ui statistic" id="boxtext-{{ $rsi }}" style="{{ $display[1] }}">
                                <div class="value" id="text-{{ $rsi }}">
                                    text-{{ $rsi }}
                                </div>
                            </div>
                        </center>
                        
                        <div id="boxtable-{{ $rsi }}" style="{{ $display[2] }}">
                            <table id="table-{{ $rsi }}" class="display nowrap" style="width: 100%;">
                                <thead>
                                    <tr>
                                        <th>hasil</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>belum ada hasil table-{{ $rsi }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    
                </div>
            </div>
            
        </div>
        
        <script>
            const ctx_{{ $r_i }} = document.getElementById('chart-{{ $rsi }}');
            
            datas{{ $rai }} = {
                type: '{{ $item["type"] }}',
                labels: '{{ $item["type"] }}' == 'text' ? [0] : null,
                dataset: null,
                title: '',
                raw: null
            }
            
            var chart_{{ $r_i }}
            function createChart_{{ $r_i }}(type, label, dataset) {
                label = label ?? ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange']
                dataset = dataset ?? [{
                    label: '# of Votes',
                    data: Array.from({length: 6}, () => Math.floor(Math.random() * 100 + 100)),
                }]
                chart_{{ $r_i }} = new Chart(ctx_{{ $r_i }}, {
                    type: type,
                    data: {
                        labels: label,
                        datasets: dataset
                    },
                    options: {
                        maintainAspectRatio: false,
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                })
            }
            
            var table_{{ $r_i }}
            function createTable_{{ $r_i }}(data = null) {
                let dataSet = [["Belum ada data"]]
                let cols = [{ 'title': 'Hasil' }]
                
                if (data) {
                    dataSet = transformDataset('table', data)
                    columns = Object.keys(data[0])
                    cols = columns.map((val) => ({ 'title': val }))
                    cols = [{'title': '#'}].concat(cols)
                }
                
                table_{{ $r_i }} = $('#table-{{ $rsi }}').DataTable( {
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
                    lengthMenu: [5, 10, 20, 50, 100, 200, 500],
                    scrollX: true
                } )
            }
            
            function runQuery_{{ $r_i }}(id) {
                $("#header-{{ $rsi }}").text("mengambil data...")
                $.get( "/api/run-query-by-id/"+id)
                .done(function( data ) {
                    type = $("#type-{{ $rsi }}").val()
                    datas{{ $rai }} = {
                        type: type,
                        labels: data.label,
                        dataset: transformDataset(datas{{ $rai }}.type, data.data, data.keys),
                        title: data.name,
                        raw: data.data
                    }
                    
                    visualize_{{ $r_i }}()
                })
            }
            
            function visualize_{{ $r_i }}() {
                const type = datas{{ $rai }}.type
                const labels = datas{{ $rai }}.labels
                const dataset = datas{{ $rai }}.dataset
                const title = datas{{ $rai }}.title
                const raw = datas{{ $rai }}.raw
                
                switch (type) {
                    case 'text':
                    const text = labels ? labels[0] : 0
                    $("#text-{{ $rsi }}").text(text.toLocaleString())
                    break;
                    
                    case 'table':
                    table_{{ $r_i }}.destroy()
                    $('#table-{{ $rsi }}').empty()

                    createTable_{{ $r_i }}(raw)
                    break;
                    
                    default:
                    if (chart_{{ $r_i }}) {
                        chart_{{ $r_i }}.destroy()
                    }
                    createChart_{{ $r_i }}(type, labels, dataset)
                    break;
                }
                
                $("#header-{{ $rsi }}").text(title)
            }
            
            createTable_{{ $r_i }}()
            visualize_{{ $r_i }}()
            
            if ({{ $item["query"] }} != 0) {
                runQuery_{{ $r_i }}({{ $item["query"] }})
            }
            
            $("#type-{{ $rsi }}").on( "change", function() {
                datas{{ $rai }}['type'] = $("#type-{{ $rsi }}").val()
                visualize_{{ $r_i }}()
                
            } );
            
            $("#query-{{ $rsi }}").on( "change", function() {
                id = $("#query-{{ $rsi }}").val()
                runQuery_{{ $r_i }}(id)
            } );
        </script>
        
        @empty
        <div class="col-sm border border-primary">Belum ada chart</div>
        @endforelse
    </div>
    @endforeach
</div>
</x-volt-panel>

<button type="button" class="ui button large primary" id="add-row" wire:click="addRow">Tambah baris</button>

{!! form()->button('Hapus semua')->id('del-all')->addClass('large inverted red') !!}

<br><br>

<div class="field">
    <div class="ui toggle checkbox">
        <input type="checkbox" tabindex="0" class="hidden" id="preview">
        <label>Preview</label>
    </div>
</div>

<textarea name="components" id="components" wire:model="componentsJson" style="display: none"></textarea>

<script>            
    $("#preview").on( "change", function() {
        isPreview = $("#preview").prop("checked")
        if (isPreview) {
            $(".tambah").hide()
            $(".editor-item").hide()
        } else {
            $(".tambah").show()
            $(".editor-item").show()
        }
    } )
    
    window.addEventListener('dehydrate', event => {            
        {{ $dehydrateThis }}
    })
    
    window.addEventListener('movePrevRow', event => {            
        const row = event.detail.row
        if (row > 0) {
            const temp = datas[row]
            datas[row] = datas[row-1]
            datas[row-1] = temp
        }
    })
    
    window.addEventListener('moveNextRow', event => {            
        const row = event.detail.row
        if (datas.length > row+1) {
            const temp = datas[row]
            datas[row] = datas[row+1]
            datas[row+1] = temp
        }
    })
    
    window.addEventListener('moveNextCol', event => {            
        const row = event.detail.row
        const col = event.detail.col
        if (datas[row].length > col+1) {
            const temp = datas[row][col]
            datas[row][col] = datas[row][col+1]
            datas[row][col+1] = temp
        }
    })
    
    window.addEventListener('movePrevCol', event => {            
        const row = event.detail.row
        const col = event.detail.col
        if (col > 0) {
            const temp = datas[row][col]
            datas[row][col] = datas[row][col-1]
            datas[row][col-1] = temp
        }
    })
    
    window.addEventListener('cl', event => {            
        console.log(event.detail)
    })
</script>
</div>
