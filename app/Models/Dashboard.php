<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Suitable\AutoFilter;
use Laravolt\Suitable\AutoSearch;
use Laravolt\Suitable\AutoSort;

class Dashboard extends Model
{
    use HasFactory;
    use AutoSort, AutoFilter, AutoSearch;

    protected $table = 'dashboard';

    protected $fillable = ['user_id', 'name', 'components'];

    protected $casts = [
        'components' => 'json'
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
