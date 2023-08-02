<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravolt\Suitable\AutoSort;

class Databases extends Model
{
    use HasFactory;
    use AutoSort;

    protected $table = 'databases';

    protected $fillable = ['name', 'host', 'port', 'type', 'dbname', 'user', 'password', 'keterangan'];
}
