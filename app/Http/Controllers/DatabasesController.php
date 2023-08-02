<?php

namespace App\Http\Controllers;

use App\Enums\DatabasesType;
use App\Http\Requests\DatabasesStoreRequest;
use App\Models\Databases;
use BenSampo\Enum\Rules\EnumValue;
use Illuminate\Http\Request;

class DatabasesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('databases.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $types = array_flip(DatabasesType::asArray());

        return view('databases.create', compact(['types']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DatabasesStoreRequest $request)
    {
        $database = Databases::create($request->validated());

        return redirect()->route('databases.index')->withSuccess('Database berhasil ditambahkan');
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
    public function edit(Databases $database)
    {
        $types = array_flip(DatabasesType::asArray());

        return view('databases.edit', compact(['database','types']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DatabasesStoreRequest $request, Databases $database)
    {
        $database->update($request->validated());
        
        return redirect()->route('databases.index')->withSuccess('Database berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Databases $database)
    {
        $database->delete();

        return redirect()->route('databases.index')->withSuccess('Database berhasil dihapus');
    }
}
