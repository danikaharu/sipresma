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
        $activityCategories = ActivityCategory::latest()->get();
        $activityTypes = ActivityType::latest()->get();
        $levels = Level::latest()->get();
        return view('admin.activity.index', compact('activityCategories', 'activityTypes', 'levels'));
    }

    public function getStudent()
    {
        $activityQuery = Activity::query();

        if (!empty(request()->filter_category)) {
            $activityQuery->where('activity_category_id', request()->filter_category);
        }
        if (!empty(request()->filter_level)) {
            $activityQuery->where('level_id', request()->filter_level);
        }
        if (!empty(request()->filter_year)) {
            $activityQuery->whereYear('date', request()->filter_year);
        }
        if (!empty(request()->filter_enrollment)) {
            $searchString = request()->filter_enrollment;
            $activityQuery->whereHas('student', function ($query) use ($searchString) {
                $query->where('enrollment_year', 'like', '%' . $searchString . '%');
            });
        }

        $activties = $activityQuery->with('award', 'student', 'category', 'level')->latest()->get();

        return dataTables()->of($activties)
            ->addIndexColumn()
            ->addColumn('student_number', function ($row) {
                return  $row->student->student_number ?? '-';
            })
            ->addColumn('student_name', function ($row) {
                return  $row->student->name ?? '-';
            })
            ->addColumn('student_enrollment', function ($row) {
                return  $row->student->enrollment_year ?? '-';
            })
            ->addColumn('category', function ($row) {
                return  $row->category->name ?? '-';
            })
            ->addColumn('level', function ($row) {
                return  $row->level->name ?? '-';
            })
            ->addColumn('award_type', function ($row) {
                return  $row->award_type() ?? '-';
            })
            ->addColumn('status', function ($row) {
                return  $row->status() ?? '-';
            })
            ->addColumn('action', 'admin.activity.include.action')
            ->rawColumns(['action'])
            ->make(true);
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
        $activityQuery = Activity::query();

        // Terapkan filter jika ada
        if (!empty($request->filter_category)) {
            $activityQuery->where('activity_category_id', $request->filter_category);
        }

        if (!empty($request->filter_level)) {
            $activityQuery->where('level_id', $request->filter_level);
        }

        if (!empty($request->filter_type)) {
            $activityQuery->where('award_type', $request->filter_type); // pastikan nama kolom benar
        }

        if (!empty($request->filter_year)) {
            $activityQuery->whereYear('date', $request->filter_year);
        }

        if (!empty($request->filter_enrollment)) {
            $searchString = $request->filter_enrollment;
            $activityQuery->whereHas('student', function ($query) use ($searchString) {
                $query->where('enrollment_year', 'like', '%' . $searchString . '%');
            });
        }

        // Ambil data lengkap beserta relasi
        $activities = $activityQuery->with('award', 'student', 'category', 'level')->latest()->get();

        // Cek jika tidak ada data
        if ($activities->isEmpty()) {
            return redirect()->back()->with('error', 'Maaf, tidak ada data yang sesuai filter untuk diekspor.');
        }

        $pdf = Pdf::loadView('admin.activity.export', compact('activities'))
            ->setPaper('A4', 'portrait');

        return $pdf->stream('DAFTAR KEGIATAN MAHASISWA.pdf');
    }
}
