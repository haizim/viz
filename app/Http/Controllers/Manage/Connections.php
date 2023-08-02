<?php

namespace App\Http\Controllers\Manage;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Database\PDO\Connection;
use Illuminate\Http\Request;
use PDO;

class Connections extends Controller
{
    public function __invoke(Request $request)
    {
        $dsn = 'pgsql:host=localhost;port=5432;dbname=mining;';
        $user = 'postgres';
        $password = 'esdfgh';
        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        // $konek = new Connection($pdo);
        $query = "select * from twit limit 5";
        // dd($konek->exec($query));

        $a = $pdo->query($query);
        $res = $a->fetchAll(PDO::FETCH_ASSOC);
        dd($res);

        // return view('home');
        return "haii";
    }
}