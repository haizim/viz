<?php

namespace App\Http\Livewire\Table;

use App\Models\Queries;
use Laravolt\Ui\TableView;
use Laravolt\Suitable\Columns\RowNumber;
use Laravolt\Suitable\Columns\Text;
use Laravolt\Suitable\Columns\RestfulButton;

class QueriesTable extends TableView
{
    public function data()
    {
        $query = Queries::query();

        if (!auth()->user()->can('queries::manage-all')) {
            $query = $query->where('user_id', auth()->id());
        }

        return $query->paginate(5);
    }

    public function columns(): array
    {
        return [
            RowNumber::make('No.'),
            Text::make('name', 'Nama')->sortable(),
            Text::make('database.name', 'Nama Database')->sortable(),
            Text::make('user.name', 'Owner')->sortable(),
            RestfulButton::make('queries', 'Action')->except('show'),
        ];
    }
}
