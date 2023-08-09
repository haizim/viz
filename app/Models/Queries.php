<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Suitable\AutoFilter;
use Laravolt\Suitable\AutoSearch;
use Laravolt\Suitable\AutoSort;

class Queries extends Model
{
    use HasFactory;
    use AutoSort, AutoFilter, AutoSearch;

    protected $table = 'queries';

    protected $fillable = ['user_id', 'name', 'database_id', 'query'];

    public function database()
    {
        return $this->hasOne(Databases::class, 'id', 'database_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
