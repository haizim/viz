<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Suitable\AutoSort;

class Queries extends Model
{
    use HasFactory;
    use AutoSort;

    protected $table = 'queries';

    protected $fillable = ['user_id', 'name', 'database_id', 'query'];

    public function database()
    {
        return $this->hasOne(Databases::class, 'id', 'database_id');
    }
}
