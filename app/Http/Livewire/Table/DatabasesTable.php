<?php

namespace App\Http\Livewire\Table;

use App\Enums\DatabasesType;
use App\Models\Databases;
use Laravolt\Suitable\Columns\Raw;
use Laravolt\Suitable\Columns\RowNumber;
use Laravolt\Suitable\Columns\Text;
use Laravolt\Suitable\Columns\RestfulButton;
use Laravolt\Ui\TableView;

class DatabasesTable extends TableView
{
    public function data()
    {
        $query = Databases::query();

        if (!auth()->user()->can('databases::manage-all')) {
            $query = $query->where('user_id', auth()->id());
        }

        return $query->orderBy('updated_at', 'desc')->paginate(5);
    }

    public function columns(): array
    {
        return [
            RowNumber::make('No.'),
            Text::make('name', 'Nama')->sortable(),
            Raw::make(
                function ($database) {
                    $tipe = $database->type;
                    
                    return DatabasesType::getKey($tipe);
                },
                'Tipe Database'
            ),
            Text::make('owner.name', 'Owner')->sortable(),
            Text::make('host', 'Host')->sortable(),
            Text::make('port', 'Port'),
            Text::make('dbname', 'Nama Database')->sortable(),
            RestfulButton::make('databases', 'Action')->except('show'),
        ];
    }
}
