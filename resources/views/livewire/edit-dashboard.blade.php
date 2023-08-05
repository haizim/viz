<div class="editor">
    <x-volt-panel title="Tambah Dashboard" icon="plus">
        <div>
            @foreach ($components as $r => $row)
            {{-- <center> --}}
                <div class="tambah down">
                    <span wire:click="movePrevRow({{ $r }})">
                        <x-volt-icon name="arrow-up"/>
                    </span>
                    <span wire:click="moveNextRow({{ $r }})">
                        <x-volt-icon name="arrow-down"/>
                    </span>.
                    <span wire:click="addCol({{ $r }})">
                        <x-volt-icon name="plus"/>
                    </span>.
                    <span wire:click="delRow({{ $r }})">
                        <x-volt-icon name="trash"/>
                    </span>.
                    <span>
                        <x-volt-icon name="arrows-v"/>
                        <input type="text" style="width: 5em; height: 3em;" wire:model="components.{{ $r }}.height">
                    </span>
                </div>
                {{-- </center> --}}
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
                                    <h4 id="header-{{ $rsi }}"></h4>
                                </div>
                            </div>
                            
                            <div class="content">
                                <div class="fields editor-item">
                                    <div class="field">
                                        <select class="ui search dropdown" wire:model="components.{{ $rii }}.type" id="type-{{ $rsi }}">
                                            <option value="">Select Chart</option>
                                            @foreach ($chartTypes as $ct)
                                            <option value="{{ $ct }}">{{ $ct }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field">
                                        <select class="ui search dropdown" wire:model="components.{{ $rii }}.query" id="query-{{ $rsi }}">
                                            <option value="0">Select Query</option>
                                            @foreach ($queries as $q)
                                            <option value="{{ $q['id'] }}">{{ $q['name'] }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="field">
                                        <input type="text" id="width-{{ $rsi }}" wire:model="components.{{ $rii }}.width" placeholder="width(%)" >
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
                                    <canvas id="chart-{{ $rsi }}"></canvas>
                                    <div class="ui statistic" style="display: none">
                                        <div class="value" id="value-{{ $rsi }}">
                                            40,509
                                        </div>
                                    </div>
                                </div>
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
                        
                        $("#type-{{ $rsi }}").on( "change", function() {
                            type_{{ $r_i }} = $("#type-{{ $rsi }}").val()
                            
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
            
            $("#preview").on( "change", function() {
                isPreview = $("#preview").prop("checked")
                console.log("isPreview : ", isPreview)
                if (isPreview) {
                    $(".tambah").hide()
                    $(".editor-item").hide()
                } else {
                    $(".tambah").show()
                    $(".editor-item").show()
                }
            } )
        </script>
    </div>
    