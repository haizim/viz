<?php

namespace App\Http\Controllers;

use App\Models\Databases;
use App\Models\Queries;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use PDO;

class ApiController extends Controller
{
    private function queryRunner($database_id, $query)
    {
        $db = Databases::find($database_id);
        
        $dbType = strtolower($db->type);
        $dbHost = $db->host;
        $dbPort = $db->port;
        $dbName = $db->dbname;
        $dbUser = $db->user;
        $dbPass = $db->password;

        $dsn = "$dbType:host=$dbHost;port=$dbPort;dbname=$dbName;";
        $pdo = new PDO($dsn, $dbUser, $dbPass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
        
        $run = $pdo->query($query);
        $result = $run->fetchAll(PDO::FETCH_ASSOC);
        
        return $result;
    }

    public function testConnection(Request $request)
    {
        $type = strtolower($request['type']);
        $host = $request['host'];
        $port = $request['port'];
        $dbName = $request['dbname'];
        $user = $request['user'];
        $password = $request['password'];

        $dsn = "$type:host=$host;port=$port;dbname=$dbName;";
        $pdo = new PDO($dsn, $user, $password, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

        $now = $pdo->query('SELECT NOW()')->fetchColumn();

        if ($now) {
            return true;
        }
    }

    public function runQuery(Request $request)
    {
        $database_id = $request['db'];
        $query = $request['query'];
        
        $result = $this->queryRunner($database_id, $query);

        $n = 1;
        foreach ($result as $key => $value) {
            $result[$key] = array_merge(["#" => $n], $result[$key]);
            $n++;
        }

        return $result;
    }

    public function runQueryDatatable(Request $request)
    {
        $data = $this->runQuery($request);

        $result = [
            "draw" => 1,
            "recordsTotal" => count($data),
            "recordsFiltered" => count($data),
            "data" => $data
        ];

        return $result;
    }

    public function runQueryById($id)
    {
        $query = Queries::find($id);
        
        $data = $this->queryRunner($query->database_id, $query->query);

        $keys = array_keys($data[0]);
        $label = array_values(array_unique(Arr::pluck($data, $keys[0])));

        $dataset = [];
        for ($i=1; $i < count($keys); $i++) {
            $key = $keys[$i];
            $dataset[$i-1]['label'] = $key;
            $dataset[$i-1]['data'] = Arr::pluck($data, $key);
        }
        
        $result['name'] = $query->name;
        $result['data'] = $data;
        $result['keys'] = $keys;
        $result['label'] = $label;
        $result['dataset'] = $dataset;
        $result['query'] = $query->query;
        
        return $result;
    }
}
