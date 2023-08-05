<?php

namespace App\Http\Livewire;

use App\Models\Queries;
use Livewire\Component;

class EditDashboard extends Component
{
    public $components = [
        [
            "height" => "250px",
            "items" =>[
                [
                    "query" => 0,
                    "type" => "line",
                    "width" => null
                ]
            ]
        ]
    ];
    public $componentsJson = "{}";
    public $queries = [];
    public $chartTypes = ["number", "bar", "doughnut", "line", "pie", "polarArea", "radar"];
    // others chart :  "bubble", "scatter" 

    public function render()
    {
        return view('livewire.edit-dashboard');
    }

    public function mount($components = null)
    {
        if (isset($components)) {
            $componentsUse = $components ? json_decode($components, true) : $this->components;
            $this->components = $componentsUse;
            $this->componentsJson = json_encode($this->components);
        }

        $query = Queries::query();

        if (!auth()->user()->can('queries::manage-all')) {
            $query = $query->where('user_id', auth()->id());
        }
        $this->queries = $query->get(['id', 'name'])->toArray();
    }
    
    public function updatedComponents()
    {
        $this->componentsJson = json_encode($this->components);
    }

    // Row Operation
    public function newRow()
    {
        return [ "height" => "250px", "items" => [$this->newColumn()] ];
    }
    public function addRow()
    {

        array_push($this->components, $this->newRow());
    }

    public function delRow($row)
    {
        unset($this->components[$row]);
    }

    public function movePrevRow($row)
    {
        if ($row > 0){
            [$this->components[$row], $this->components[$row-1]] = [$this->components[$row-1], $this->components[$row]];
        }
    }

    public function moveNextRow($row)
    {
        if (count($this->components) > $row+1){
            [$this->components[$row], $this->components[$row+1]] = [$this->components[$row+1], $this->components[$row]];
        }
    }

    // column operation
    public function newColumn()
    {
        $types = $this->chartTypes;
        $type = $types[rand(1, count($types)-1)];
        return [
            "query" => 0,
            "type" => $type,
            "width" => null
        ];
    }

    public function addCol($row)
    {
        array_push($this->components[$row]['items'], $this->newColumn());
        if(count($this->components[$row]) < 3) {
        }
    }

    public function delCol($row, $col)
    {
        unset($this->components[$row][$col]);
    }

    public function movePrevCol($row, $col)
    {
        if ($col > 0){
            [$this->components[$row][$col], $this->components[$row][$col-1]] = [$this->components[$row][$col-1], $this->components[$row][$col]];
        }
    }

    public function moveNextCol($row, $col)
    {
        if (count($this->components[$row]) > $col+1){
            [$this->components[$row][$col], $this->components[$row][$col+1]] = [$this->components[$row][$col+1], $this->components[$row][$col]];
        }
    }
}
