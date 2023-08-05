<div class="editor">
    <div>
        @foreach ($components as $r => $row)
        <div class="ui equal width grid baris">
            @forelse ($row['items'] as $i => $item)
            
            @php
            $r_i = $r."_$i";
            $rsi = $r."-$i";
            $rii = $r.'.items.'.$i;
            $width = $item['width'] ?? "25";
            @endphp
            
            <div class="column p-1" style="max-width:{{ $width }}%">

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
                            <canvas id="chart-{{ $rsi }}"></canvas>
                        </div>
                        
                        <div class="ui statistic" style="display: none">
                            <div class="value" id="value-{{ $rsi }}">
                                40,509
                            </div>
                        </div>
                        
                        <textarea id="query-{{ $rsi }}" style="display: none"></textarea>
                    </div>
                </div>

            </div>
            
            <script>
                const ctx_{{ $r_i }} = document.getElementById('chart-{{ $rsi }}');
                console.log('ctx_{{ $r_i }} : ', ctx_{{ $r_i }})
                
                let type_{{ $r_i }} = '{{ $item["type"] }}'
                let labels_{{ $r_i }}
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
                        // result = JSON.parse(data)
                        console.log(data)
                        labels_{{ $r_i }} = data.label
                        dataset_{{ $r_i }} = transformDataset(type_{{ $r_i }}, data.data, data.keys)
                        console.log("type :", type_{{ $r_i }}, "label :", labels_{{ $r_i }})
                        console.log("data.dataset : ", data.dataset)
                        console.log("dataset transformed :", dataset_{{ $r_i }})
                        
                        $("#header-{{ $rsi }}").text(data.name)
                        
                        $("#query-{{ $rsi }}").val(data.query)
                        $('#btn-query-{{ $rsi }}').prop('disabled', false)
                        visualize_{{ $r_i }}()
                    })
                }
                
                function visualize_{{ $r_i }}() {
                    if (type_{{ $r_i }} == 'number') {
                        
                    } else {
                        chart_{{ $r_i }}.destroy()
                        createChart_{{ $r_i }}(type_{{ $r_i }}, labels_{{ $r_i }}, dataset_{{ $r_i }})
                    }
                }
                
                createChart_{{ $r_i }}(type_{{ $r_i }})
                
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
                <textarea id="show-query-{{ $rsi }}" rows="10" style="width: 100%" readonly></textarea>
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
                    console.log("transformDataset line type : not number")
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
            console.log("result pivotDataLine :", result)
            dataset = []
            
            for (const [key, value] of Object.entries(result)) {
                ds = {
                    "label" : key,
                    "yAxis" : key,
                    "data" : value,
                }
                dataset.push(ds)
            }
            
            console.log("dataset pivotDataLine :", dataset)
            
            return dataset
        }
        
        function showQuery(id)
        {
            query = $("#"+id).val()
            console.log("query : ", query)
            $("#show-query-{{ $rsi }}").val(query)
            $("#modal-query").modal('show')
        }
    </script>
</div>
