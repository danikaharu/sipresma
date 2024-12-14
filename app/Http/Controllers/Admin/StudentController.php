<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class StudentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $students = Student::latest()->get();
            return DataTables::of($students)
                ->addIndexColumn()
                ->addColumn('program', function ($row) {
                    return  $row->program();
                })
                ->addColumn('action', 'admin.student.include.action')
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.student.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.student.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreStudentRequest $request)
    {
        try {
            $attr = $request->validated();

            Student::create($attr);

            return redirect()->route('admin.student.index')->with('success', 'Data berhasil ditambah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.student.index')->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Student $student)
    {
        return view('admin.student.show', compact('student'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Student $student)
    {
        return view('admin.student.edit', compact('student'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateStudentRequest $request, Student $student)
    {
        try {
            $attr = $request->validated();

            $student->update($attr);

            return redirect()
                ->route('admin.student.index')
                ->with('success', __('Data Berhasil Diubah'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.student.index')
                ->with('error', __($th->getMessage()));
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Student $student)
    {
        try {

            $student->delete();

            return redirect()
                ->route('admin.student.index')
                ->with('success', __('Data Berhasil Dihapus'));
        } catch (\Throwable $th) {
            return redirect()
                ->route('admin.student.index')
                ->with('error', __($th->getMessage()));
        }
    }

    public function studentsWith30Points()
    {
        $students = DB::table('students')
            ->select('students.id', 'students.name', DB::raw('SUM(awards.point) as total_points'))
            ->join('activities', 'students.id', '=', 'activities.student_id')
            ->join('awards', 'activities.award_id', '=', 'awards.id')
            ->where('activities.status', 1) // Hanya kegiatan yang valid
            ->groupBy('students.id', 'students.name') // Group berdasarkan mahasiswa
            ->get();

        $students = $students->map(function ($student) {
            $student->name = $student->name;
            $student->total_points = (int) $student->total_points;
            return $student;
        });

        if (request()->ajax()) {
            return DataTables::of($students)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {
                    return  $row->name;
                })
                ->addColumn('total_point', function ($row) {
                    return  $row->total_points;
                })
                ->addColumn('action', function ($row) {
                    return '<a href="/admin/generate-certificate/' . $row->id . '" class="btn btn-info" target="_blank">Generate Sertifikat</a>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.student.with_points');
    }

    public function generateCertificate($studentId)
    {
        // Ambil data mahasiswa berdasarkan ID
        $student = Student::findOrFail($studentId);

        // Hitung total poin mahasiswa
        $totalPoints = DB::table('activities')
            ->join('awards', 'activities.award_id', '=', 'awards.id')
            ->where('activities.student_id', $studentId)
            ->where('activities.status', 1) // Hanya status valid
            ->sum('awards.point');

        // Cek apakah mahasiswa memenuhi syarat untuk sertifikat (misalnya, mencapai 30 poin)
        if ($totalPoints >= 30) {
            // Load view sertifikat dan pass data ke PDF
            $pdf = Pdf::loadView('admin.student.certificate', compact('student', 'totalPoints'));

            // Simpan atau download sertifikat sebagai PDF
            return $pdf->download('sertifikat-' . $student->name . '.pdf');
        } else {
            return redirect()->back()->with('error', 'Mahasiswa belum mencapai 30 poin.');
        }
    }
}
