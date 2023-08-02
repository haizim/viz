<?php

namespace App\Http\Livewire\Table;

use Laravolt\Suitable\Columns\RowNumber;
use Laravolt\Suitable\Columns\Text;
use Laravolt\Suitable\Columns\RestfulButton;
use Laravolt\Ui\TableView;

class DashboardTable extends TableView
{
    public function data()
    {
        return \App\Models\Dashboard::paginate(5);
    }

    public function columns(): array
    {
        return [
            RowNumber::make('No.'),
            Text::make('name', 'Nama')->sortable(),
            RestfulButton::make('dashboard', 'Action'),
        ];
    }
}
