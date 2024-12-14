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
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ActivityController extends Controller
{
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
        return view('admin.activity.create', compact('categories', 'levels'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreActivityRequest $request)
    {
        try {
            $attr = $request->validated();

            $uploadedFiles = [];
            if ($request->hasFile('file')) {
                foreach ($request->file('file') as $file) {
                    if ($file->isValid()) {
                        $filename = $file->hashName();
                        $file->storeAs('upload/file/', $filename, 'public');
                        $uploadedFiles[] = $filename;
                    }
                }
            }

            $attr['file'] = json_encode($uploadedFiles);

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
        $data = json_decode($activity['file'], true);

        // Proses array menjadi string (contoh untuk warna)
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $data[$key] = implode(', ', $value);
            }
        }

        return view('admin.activity.show', compact('activity', 'data'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Activity $activity)
    {
        $categories = ActivityCategory::all();
        $levels = Level::all();
        return view('admin.activity.edit', compact('activity', 'categories', 'levels'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateActivityRequest $request, Activity $activity)
    {
        try {
            $attr = $request->validated();

            if ($request->hasFile('file') && is_array($request->file('file'))) {
                $path = storage_path('app/public/upload/file/');

                // Inisialisasi array untuk menyimpan nama file gambar
                $filenames = [];

                // Cek jika ada file yang diupload
                foreach ($request->file('file') as $file) {
                    if ($file->isValid()) { // Pastikan file valid
                        $filename = $file->hashName();

                        // Simpan file di folder yang sesuai
                        if (!file_exists($path)) {
                            mkdir($path, 0777, true); // Membuat direktori jika belum ada
                        }

                        $file->storeAs('upload/file/', $filename, 'public');
                        $filenames[] = $filename; // Menambahkan nama file ke array
                    }
                }

                // Hapus gambar lama jika ada
                if ($activity->file != null) {
                    $oldImages = json_decode($activity->file); // Mengambil nama gambar lama
                    foreach ($oldImages as $image) {
                        $oldImagePath = $path . $image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath); // Menghapus file lama
                        }
                    }
                }

                // Simpan nama file gambar dalam bentuk JSON di database
                $attr['file'] = json_encode($filenames);
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
                $path = storage_path('app/public/upload/file/');
                if ($activity->file != null) {
                    $oldImages = json_decode($activity->file); // Mengambil nama gambar lama
                    foreach ($oldImages as $image) {
                        $oldImagePath = $path . $image;
                        if (file_exists($oldImagePath)) {
                            unlink($oldImagePath); // Menghapus file lama
                        }
                    }
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
}
