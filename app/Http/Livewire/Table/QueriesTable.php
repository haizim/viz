<?php

namespace App\Http\Livewire\Table;

use Laravolt\Ui\TableView;
use Laravolt\Suitable\Columns\RowNumber;
use Laravolt\Suitable\Columns\Text;
use Laravolt\Suitable\Columns\RestfulButton;

class QueriesTable extends TableView
{
    public function data()
    {
        return \App\Models\Queries::paginate(5);
    }

    public function columns(): array
    {
        return [
            RowNumber::make('No.'),
            Text::make('name', 'Nama')->sortable(),
            Text::make('database.name', 'Nama Database')->sortable(),
            RestfulButton::make('queries', 'Action')->except('show'),
        ];
    }
}
