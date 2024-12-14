<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityCategory;
use App\Http\Requests\StoreActivityCategoryRequest;
use App\Http\Requests\UpdateActivityCategoryRequest;
use Yajra\DataTables\Facades\DataTables;

class ActivityCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $categories = ActivityCategory::latest()->get();
            return DataTables::of($categories)
                ->addIndexColumn()
                ->addColumn('action', 'admin.activity-category.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.activity-category.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.activity-category.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityCategoryRequest $request)
    {
        try {
            $attr = $request->validated();

            ActivityCategory::create($attr);

            return redirect()->route('admin.activity-category.index')->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.activity-category.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ActivityCategory $activityCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ActivityCategory $activityCategory)
    {
        return view('admin.activity-category.edit', compact('activityCategory'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityCategoryRequest $request, ActivityCategory $activityCategory)
    {
        try {
            $attr = $request->validated();

            $activityCategory->update($attr);

            return redirect()
                ->route('admin.activity-category.index')
                ->with('success', __('Data Berhasil Diubah'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.activity-category.index')
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ActivityCategory $activityCategory)
    {
        try {

            $activityCategory->delete();

            return redirect()
                ->route('admin.activity-category.index')
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.activity-category.index')
                ->with('error', __($th->getMessage()));
        }
    }
}
