<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Activity;
use App\Http\Requests\StoreActivityRequest;
use App\Http\Requests\UpdateActivityRequest;
use App\Models\ActivityCategory;
use App\Models\ActivityType;
use App\Models\Award;
use App\Models\Level;
use App\Models\Student;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller implements HasMiddleware
{


    public static function middleware(): array
    {
        return [
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('view activity'), only: ['index']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('create activity'), only: ['create', 'store']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('show activity'), only: ['show']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('edit activity'), only: ['edit', 'update']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('delete activity'), only: ['destroy']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('export activity'), only: ['export']),
            new Middleware(\Spatie\Permission\Middleware\PermissionMiddleware::using('verify activity'), only: ['updateStatus']),
        ];
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $activities = Activity::with('award', 'student', 'category', 'level')->latest()->get();
            return DataTables::of($activities)
                ->addIndexColumn()
                ->addColumn('student', function ($row) {
                    return  $row->student->name ?? '-';
                })
                ->addColumn('category', function ($row) {
                    return  $row->category->name ?? '-';
                })
                ->addColumn('level', function ($row) {
                    return  $row->level->name ?? '-';
                })
                ->addColumn('point', function ($row) {
                    return  $row->award->point ?? '-';
                })
                ->addColumn('status', function ($row) {
                    return  $row->status() ?? '-';
                })
                ->addColumn('action', 'admin.activity.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('admin.activity.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categories = ActivityCategory::all();
        $levels = Level::all();
        $students = Student::all();
        return view('admin.activity.create', compact('categories', 'levels', 'students'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request)
    {
        try {
            $attr = $request->validated();

            if ($request->file('photo') && $request->file('photo')->isValid()) {

                $filename = $request->file('photo')->hashName();
                $request->file('photo')->storeAs('upload/kegiatan', $filename, 'public');

                $attr['photo'] = $filename;
            }

            if ($request->file('file') && $request->file('file')->isValid()) {

                $filename = $request->file('file')->hashName();
                $request->file('file')->storeAs('upload/sertifikat', $filename, 'public');

                $attr['file'] = $filename;
            }


            Activity::create($attr);

            return redirect()->route('admin.activity.index')->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.activity.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Activity $activity)
    {
        return view('admin.activity.show', compact('activity'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        $categories = ActivityCategory::all();
        $levels = Level::all();
        $students = Student::all();
        return view('admin.activity.edit', compact('activity', 'categories', 'levels', 'students'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        try {
            $attr = $request->validated();

            if ($request->file('photo') && $request->file('photo')->isValid()) {

                $path = storage_path('app/public/upload/kegiatan/');
                $filename = $request->file('photo')->hashName();
                $request->file('photo')->storeAs('upload/kegiatan', $filename, 'public');

                // delete old file from storage
                if ($activity->photo != null && file_exists($path . $activity->photo)) {
                    unlink($path . $activity->photo);
                }

                $attr['photo'] = $filename;
            }

            if ($request->file('file') && $request->file('file')->isValid()) {

                $path = storage_path('app/public/upload/sertifikat/');
                $filename = $request->file('file')->hashName();
                $request->file('file')->storeAs('upload/sertifikat', $filename, 'public');

                // delete old file from storage
                if ($activity->file != null && file_exists($path . $activity->file)) {
                    unlink($path . $activity->file);
                }

                $attr['file'] = $filename;
            }

            $activity->update($attr);

            return redirect()
                ->route('admin.activity.index')
                ->with('success', __('Data Berhasil Diubah'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.activity.index')
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Activity $activity)
    {
        try {
            if ($activity) {
                $filePath = storage_path('app/public/upload/sertifikat/');
                $photoPath = storage_path('app/public/upload/kegiatan/');

                if ($activity->file != null && file_exists($filePath . $activity->file)) {
                    unlink($filePath . $activity->file);
                }

                if ($activity->photo != null && file_exists($photoPath . $activity->photo)) {
                    unlink($photoPath . $activity->photo);
                }

                $activity->delete();
            }

            return redirect()
                ->route('admin.activity.index')
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.activity.index')
                ->with('error', __($th->getMessage()));
        }
    }

    public function getActivityDetails(Request $request)
    {
        $categoryId = $request->input('category_id');

        $activityTypes = ActivityType::where('activity_category_id', $categoryId)->get();

        $awards = Award::where('activity_category_id', $categoryId)->get();

        return response()->json([
            'types' => $activityTypes,
            'awards' => $awards
        ]);
    }

    public function updateStatus($activityId, $status)
    {
        $activity = Activity::findOrFail($activityId);

        // Validate the status value
        if (!in_array($status, [1, 2])) {
            return redirect()->route('admin.activity.index')->with('error', 'Invalid status.');
        }

        // Update the activity's status based on the action
        $activity->status = $status;
        $activity->save();  // Save the changes

        return redirect()->route('admin.activity.show', $activity->id)
            ->with('status', 'Data berhasil divalidasi');
    }

    public function export(Request $request)
    {
        $activities = Activity::with('award', 'student')
            ->latest()
            ->get();

        $pdf = Pdf::loadView('admin.activity.export', compact('activities'))
            ->setPaper('A4', 'portrait');

        if ($activities) {
            return $pdf->stream('DAFTAR KEGIATAN MAHASISWA.pdf');
        } else {
            return redirect()->back()->with('toast_error', 'Maaf, tidak bisa export data');
        }
    }
}
