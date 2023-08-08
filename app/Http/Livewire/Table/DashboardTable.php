<?php

namespace App\Http\Livewire\Table;

use App\Models\Dashboard;
use Laravolt\Suitable\Columns\RowNumber;
use Laravolt\Suitable\Columns\Text;
use Laravolt\Suitable\Columns\RestfulButton;
use Laravolt\Suitable\Columns\Button;
use Laravolt\Ui\TableView;

class DashboardTable extends TableView
{
    public function data()
    {
        
        $query = Dashboard::query();

        if (!auth()->user()->can('dashboard::manage-all')) {
            $query = $query->where('user_id', auth()->id());
        }

        return $query->orderBy('updated_at', 'desc')->paginate(5);
    }

    public function columns(): array
    {
        return [
            RowNumber::make('No.'),
            Text::make('name', 'Nama')->sortable(),
            Text::make('user.name', 'Owner')->sortable(),
            RestfulButton::make('dashboard', 'Action')->except('show'),
            Button::make(fn ($dashboard) => route('dashboard-show', $dashboard['id']), '')->label('View')->icon('eye'),
        ];
    }
}
