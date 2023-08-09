<div class="editor">
    <div>
        <link href="/css/jquery.dataTables.min.css" rel="stylesheet" />
        <script src="/js/jquery.dataTables.min.js"></script>
        <script>
            let datas = [];
        </script>
        @foreach ($components as $r => $row)
        <div class="ui stackable equal width grid baris">
            <script>
                datas[{{ $r }}] = [];
            </script>
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
            
            @endphp
            
            <div class="column p-1 item" style="max-width:{{ $width }}%">
                
                <div class="ui card" style="width: 100%">
                    
                    <div class="content">
                        <div class="header">
                            <div class="ui equal width grid">
                                <div class="column" style="width:80%">
                                    <h4 id="header-{{ $rsi }}"></h4>
                                </div>
                                <div class="column" style="text-align: right; width:20%">
                                    <button id="btn-query-{{ $rsi }}" class="mini ui icon button" onclick="showQuery('query-{{ $rsi }}')" disabled>
                                        <x-volt-icon name="code"></x-volt-icon>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="content">
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
                                <table id="table-{{ $rsi }}" class="display nowrap" style="width: 100%;"></table>
                            </div>
                            
                        </div>
                        
                        <textarea id="query-{{ $rsi }}" style="display: none"></textarea>
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
                    $('#btn-query-{{ $rsi }}').prop('disabled', true)
                    $.get( "/api/run-query-by-id/"+id)
                    .done(function( data ) {
                        datas{{ $rai }} = {
                            type: '{{ $item["type"] }}',
                            labels: data.label,
                            dataset: transformDataset(datas{{ $rai }}.type, data.data, data.keys),
                            title: data.name,
                            raw: data.data
                        }
                        
                        $("#header-{{ $rsi }}").text(data.name)
                        
                        $("#query-{{ $rsi }}").val(data.query)
                        $('#btn-query-{{ $rsi }}').prop('disabled', false)
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
                        $("#text-{{ $rsi }}").text(labels[0].toLocaleString())
                        break;
                        
                        case 'table':
                        createTable_{{ $r_i }}(raw)
                        break;
                        
                        default:
                        createChart_{{ $r_i }}(type, labels, dataset)
                        break;
                    }
                }
                
                runQuery_{{ $r_i }}({{ $item["query"] }})
                
            </script>
            
            @empty
            <div class="col-sm border border-primary">Belum ada chart</div>
            @endforelse
        </div>
        @endforeach
    </div>
    
    <div class="ui modal" id="modal-query">
        <div class="header">Query</div>
        <div class="content">
            <p>
                <textarea id="show-query-{{ $rsi }}" style="width: 100%; height:50vh" readonly></textarea>
            </p>
        </div>
        <div class="actions">
            <div class="ui cancel button">Close</div>
        </div>
    </div>
    
    <script>
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
        
        function showQuery(id)
        {
            query = $("#"+id).val()
            $("#show-query-{{ $rsi }}").val(query)
            $("#modal-query").modal('show')
        }
    </script>
</div>
