<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Level;
use App\Http\Requests\StoreLevelRequest;
use App\Http\Requests\UpdateLevelRequest;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $levels = Level::latest()->get();
            return DataTables::of($levels)
                ->addIndexColumn()
                ->addColumn('action', 'admin.level.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.level.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.level.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLevelRequest $request)
    {
        try {
            $attr = $request->validated();

            Level::create($attr);

            return redirect()->route('admin.level.index')->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.level.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Level $level)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Level $level)
    {
        return view('admin.level.edit', compact('level'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateLevelRequest $request, Level $level)
    {
        try {
            $attr = $request->validated();

            $level->update($attr);

            return redirect()
                ->route('admin.level.index')
                ->with('success', __('Data Berhasil Diubah'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.level.index')
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Level $level)
    {
        try {

            $level->delete();

            return redirect()
                ->route('admin.level.index')
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.level.index')
                ->with('error', __($th->getMessage()));
        }
    }
}
