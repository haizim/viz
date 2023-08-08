<div class="editor">
    <div>
        @foreach ($components as $r => $row)
        <div class="ui stackable equal width grid baris">
            @forelse ($row['items'] as $i => $item)
            
            @php
            $r_i = $r."_$i";
            $rsi = $r."-$i";
            $rii = $r.'.items.'.$i;
            $width = $item['width'] ?? "25";
            $display = [];
            $hide = "display: none;";
            
            if ($item['type'] == 'text') {
                $display = [$hide, ""];
            } else {
                $display = ["", $hide];
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
                            
                        </div>
                        
                        <textarea id="query-{{ $rsi }}" style="display: none"></textarea>
                    </div>
                </div>
                
            </div>
            
            <script>
                const ctx_{{ $r_i }} = document.getElementById('chart-{{ $rsi }}');
                
                let type_{{ $r_i }} = '{{ $item["type"] }}'
                let labels_{{ $r_i }} = type_{{ $r_i }} == 'text' ? [0] : null
                let dataset_{{ $r_i }} 
                
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
                
                function runQuery_{{ $r_i }}(id) {
                    $("#header-{{ $rsi }}").text("mengambil data...")
                    $('#btn-query-{{ $rsi }}').prop('disabled', true)
                    $.get( "/api/run-query-by-id/"+id)
                    .done(function( data ) {
                        labels_{{ $r_i }} = data.label
                        dataset_{{ $r_i }} = transformDataset(type_{{ $r_i }}, data.data, data.keys)
                        
                        $("#header-{{ $rsi }}").text(data.name)
                        
                        $("#query-{{ $rsi }}").val(data.query)
                        $('#btn-query-{{ $rsi }}').prop('disabled', false)
                        visualize_{{ $r_i }}()
                    })
                }
                
                function visualize_{{ $r_i }}(isLoad = false) {
                    if (type_{{ $r_i }} == 'text') {
                        $("#text-{{ $rsi }}").text(labels_{{ $r_i }}[0].toLocaleString())
                    } else {
                        if (isLoad) {
                            createChart_{{ $r_i }}(type_{{ $r_i }})
                        } else {
                            chart_{{ $r_i }}.destroy()
                            createChart_{{ $r_i }}(type_{{ $r_i }}, labels_{{ $r_i }}, dataset_{{ $r_i }})
                        }
                    }
                }
                
                visualize_{{ $r_i }}(true)
                
                if (type_{{ $r_i }} != 0) {
                    runQuery_{{ $r_i }}({{ $item["query"] }})
                }
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
        
        function transformDataset(type, data, keys)
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
