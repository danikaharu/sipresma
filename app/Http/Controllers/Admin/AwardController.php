<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Award;
use App\Http\Requests\StoreAwardRequest;
use App\Http\Requests\UpdateAwardRequest;
use App\Models\ActivityCategory;
use App\Models\Level;
use Yajra\DataTables\Facades\DataTables;

class AwardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $awards = Award::with('category', 'level')->latest()->get();
            return DataTables::of($awards)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return  $row->category->name ?? '-';
                })
                ->addColumn('level', function ($row) {
                    return  $row->level->name ?? '-';
                })
                ->addColumn('point', function ($row) {
                    return  $row->point ?? '-';
                })
                ->addColumn('action', 'admin.award.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.award.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ActivityCategory::all();
        $levels = Level::all();
        return view('admin.award.create', compact('categories', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreAwardRequest $request)
    {
        try {
            $attr = $request->validated();

            Award::create($attr);

            return redirect()->route('admin.award.index')->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.award.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Award $award)
    {
        //    
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Award $award)
    {
        $categories = ActivityCategory::all();
        $levels = Level::all();
        return view('admin.award.edit', compact('award', 'categories', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateAwardRequest $request, Award $award)
    {
        try {
            $attr = $request->validated();

            $award->update($attr);

            return redirect()
                ->route('admin.award.index')
                ->with('success', __('Data Berhasil Diubah'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.award.index')
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Award $award)
    {
        try {

            $award->delete();

            return redirect()
                ->route('admin.award.index')
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.award.index')
                ->with('error', __($th->getMessage()));
        }
    }
}
