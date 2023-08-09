<?php

namespace App\Http\Controllers;

use App\Http\Requests\DashboardStoreRequest;
use App\Models\Dashboard;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(auth()->user()->can('dashboard::manage-all'));
        return view('dashboard.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('dashboard.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DashboardStoreRequest $request)
    {
        $data = array_merge(['user_id' => auth()->id()], $request->validated());
        
        $query = Dashboard::create($data);

        return redirect()->route('dashboard.index')->withSuccess('Dashboard berhasil ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Dashboard $dashboard)
    {
        // dd($dashboard);
        return view('dashboard.show', compact(['dashboard']));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Dashboard $dashboard)
    {
        return view('dashboard.edit', compact(['dashboard']));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(DashboardStoreRequest $request, Dashboard $dashboard)
    {
        $dashboard->update($request->validated());
        
        return redirect()->route('dashboard.index')->withSuccess('Dashboard berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dashboard $dashboard)
    {
        $dashboard->delete();

        return redirect()->route('dashboard.index')->withSuccess('Dashboard berhasil dihapus');
    }
}
