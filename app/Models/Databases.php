<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Suitable\AutoFilter;
use Laravolt\Suitable\AutoSearch;
use Laravolt\Suitable\AutoSort;

class Databases extends Model
{
    use HasFactory;
    use AutoSort, AutoFilter, AutoSearch;

    protected $table = 'databases';

    protected $fillable = ['name', 'host', 'port', 'type', 'dbname', 'user', 'password', 'keterangan', 'user_id'];

    public function owner()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}
