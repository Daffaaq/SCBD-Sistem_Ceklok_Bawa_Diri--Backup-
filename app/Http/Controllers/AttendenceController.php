<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;
use App\Models\attendence;
use Illuminate\Support\Facades\Storage;

class AttendenceController extends Controller
{
    public function index()
    {
        $successMessage = session('success');
        return view('Pegawai.Absensi.index',compact('successMessage'));
    }
    public function recapAttendence()
    {
        return view('Pegawai.Absensi.rekapAbsensi');
    }
    public function getRecapAttendence()
    {
        $userId = auth()->id();

        $attendances = Attendence::where('created_by', $userId)
            ->select('id','attendences_date', 'attendences_time', 'attendance_status', 'attendences_Status')
            ->get();

        return DataTables::of($attendances)
            ->addColumn('action', function ($attendance) {
                // You can add additional columns or actions here
                return '<button class="btn btn-sm btn-info" onclick="deleteAttendance(' . $attendance->id . ')">Delete</button>';
            })
            ->make(true);
    }

    public function store(Request $request)
    {
        // Validasi input jika diperlukan
        $request->validate([
            'attendance_status' => 'required',
            'attendance_type' => 'required|in:onsite,online',
            'file' => 'nullable|file|mimes:pdf,doc,docx|max:100000',
            // ... tambahkan aturan validasi lainnya sesuai kebutuhan
        ]);

        $userId = Auth::id();

        // Map the form values to the corresponding database columns
        $attendancestatusMap = [
            'hadir' => 'hadir',
            'alfa' => 'alfa',
            'sakit' => 'sakit',
        ];

        $attendanceTypeMap = [
            'onsite' => 'onsite',
            'online' => 'online',
        ];

        // Handle file upload if present
        // Simpan data absensi baru dengan waktu dan tanggal saat ini
        $attendance = attendence::create([
            'user_id' => $userId,
            'attendences_time' => now()->format('H:i:s'),
            'attendences_date' => now()->toDateString(),
            'attendance_status' => $attendancestatusMap[$request->input('attendance_status')],
            'attendance_type' => $attendanceTypeMap[$request->input('attendance_type')],
            'attendences_Status' => 'pending_approval',
            'file' => null,
            'longitude_attendences' => $request->input('longitude_attendences'),
            'latitude_attendences' => $request->input('latitude_attendences'),
            'created_by' => $userId,
            // ... tambahkan kolom lainnya sesuai kebutuhan
        ]);
        
        if ($request->hasFile('file')) {
            $file = $request->file('file');

            // Mendapatkan nama file asli
            $originalFileName = $file->getClientOriginalName();

            // Membuat nama file baru
            $newFileName = 'user1_' . $originalFileName;

            // Menyimpan file ke direktori 'attendence_files'
            $filePath = $file->storeAs('attendence_files', $newFileName, 'public');

            // Update kolom 'file' pada record absensi
            $attendance->file = $filePath;
            $attendance->save();
        }
        // Response JSON sukses
        session()->flash('success', 'Absensi berhasil disimpan');
        return redirect('/pegawai/absensi');
    }
    public function destroy(Request $request, $id)
    {
        // Find the attendance record
        $attendance = Attendence::findOrFail($id);

        // Perform any additional validation or checks if needed

        // Delete the file associated with the attendance (if any)
        if ($attendance->file) {
            // Delete the file from storage
            Storage::disk('public')->delete($attendance->file);
        }

        // Delete the attendance record
        $attendance->delete();

        // Return a JSON response
        return response()->json(['message' => 'Attendance deleted successfully']);
    }
}
