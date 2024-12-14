<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityType;
use App\Http\Requests\StoreActivityTypeRequest;
use App\Http\Requests\UpdateActivityTypeRequest;
use App\Models\ActivityCategory;
use Yajra\DataTables\Facades\DataTables;

class ActivityTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $types = ActivityType::with('category')->latest()->get();
            return DataTables::of($types)
                ->addIndexColumn()
                ->addColumn('category', function ($row) {
                    return  $row->category->name ?? '-';
                })
                ->addColumn('action', 'admin.activity-type.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.activity-type.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ActivityCategory::all();
        return view('admin.activity-type.create', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityTypeRequest $request)
    {
        try {
            $attr = $request->validated();

            ActivityType::create($attr);

            return redirect()->route('admin.activity-type.index')->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.activity-type.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityType $activityType)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityType $activityType)
    {
        $categories = ActivityCategory::all();
        return view('admin.activity-type.edit', compact('activityType', 'categories'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityTypeRequest $request, ActivityType $activityType)
    {
        try {
            $attr = $request->validated();

            $activityType->update($attr);

            return redirect()
                ->route('admin.activity-type.index')
                ->with('success', __('Data Berhasil Diubah'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.activity-type.index')
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityType $activityType)
    {
        try {

            $activityType->delete();

            return redirect()
                ->route('admin.activity-type.index')
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.activity-type.index')
                ->with('error', __($th->getMessage()));
        }
    }
}
