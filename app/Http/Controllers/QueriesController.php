<?php

namespace App\Http\Controllers;

use App\Http\Requests\QueriesStoreRequest;
use App\Models\Databases;
use App\Models\Queries;
use Illuminate\Http\Request;

class QueriesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('queries.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $databasesQuery = "select id, name from databases";
        $databases = Databases::get(['id', 'name'])->toArray();
        
        $dbList = [];

        foreach ($databases as $db) {
            $dbList[$db['id']] = $db['name'];
        }

        return view('queries.create', compact(['dbList', 'databasesQuery']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(QueriesStoreRequest $request)
    {
        $data = array_merge(['user_id' => auth()->id()], $request->validated());
        
        $query = Queries::create($data);

        return redirect()->route('queries.index')->withSuccess('Query berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Queries $query)
    {
        $databasesQuery = "select id, name from databases";

        return view('queries.edit', compact(['databasesQuery', 'query']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(QueriesStoreRequest $request, Queries $query)
    {
        $data = array_merge(['user_id' => auth()->id()], $request->validated());

        $query->update($data);
        
        return redirect()->route('queries.index')->withSuccess('Query berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Queries $query)
    {
        $query->delete();

        return redirect()->route('queries.index')->withSuccess('Query berhasil dihapus');
    }
}
