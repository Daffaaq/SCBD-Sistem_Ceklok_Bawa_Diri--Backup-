<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\role;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UserStoreRequest;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\QrCodeMail;


class UserController extends Controller
{
    public function index(){
        
        return view('Admin.User.index');
    }
    public function create(){
        return view('Admin.User.create');
    }

    public function store(UserStoreRequest $request)
    {
        $role = Role::where('name', $request->input('role_name'))->first();

        if (!$role) {
            return response()->json(['error' => 'Invalid role.'], 400);
        }

        $user = new User([
            'uuid' => Str::uuid(),
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'no_telp' => $request->input('no_telp'),
            'jabatan' => $request->input('jabatan'),
            'role_id' => $role->id,
            'password' => bcrypt($request->input('password')),
            'remember_token' => Str::random(10),
        ]);

        $user->save();

        return redirect('/admin/users')->with('success', 'User created successfully.');
    }

    public function edit($id)
    {
        // Temukan pengguna berdasarkan ID
        $user = User::find($id);

        // Periksa apakah pengguna ditemukan
        if (!$user) {
            return redirect('/admin/users')->with('error', 'User not found.');
        }

        return view('Admin.User.edit', compact('user'));
    }
    
    public function profile()
{
    // Ambil pengguna yang sedang terautentikasi
    $user = Auth::user();

    // Periksa apakah pengguna terautentikasi
    if ($user) {
        // Lakukan sesuatu dengan $user
        // dd($user);

        // Kemudian, gunakan $user dalam tampilan atau logika lainnya
        return view('Admin.User.profile', compact('user'));
    }

    // Jika pengguna tidak terautentikasi, arahkan ke halaman login atau tempat lain
    return redirect('/admin')->with('error', 'You need to log in first.');
}


    public function update(Request $request, $id)
    {
        // Temukan pengguna berdasarkan ID
        $user = User::find($id);

        // Periksa apakah pengguna ditemukan
        if (!$user) {
            return redirect('/admin/users')->with('error', 'User not found.');
        }

        // Temukan peran baru berdasarkan nama rolenya
        $role = Role::where('name', $request->input('role_name'))->first();

        // Periksa apakah peran ditemukan
        if (!$role) {
            return redirect('/admin/users')->with('error', 'Invalid role.');
        }

        // Update data pengguna
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->no_telp = $request->input('no_telp');
        $user->jabatan = $request->input('jabatan');
        $user->role_id = $role->id;

        // Periksa apakah password diisi sebelum memperbarui
        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }

        // Simpan perubahan
        $user->save();

        return redirect('/admin/users')->with('success', 'User updated successfully.');
    }

    public function destroy($id)
    {
        // Temukan pengguna berdasarkan ID
        $user = User::find($id);

        // Periksa apakah pengguna ditemukan
        if (!$user) {
            return response()->json(['error' => 'User not found.'], 404);
        }

        // Hapus pengguna
        DB::beginTransaction();
        try {
            $user->delete();
            DB::commit();

            return response()->json(['success' => 'User deleted successfully.']);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to delete user.'], 500);
        }
    }

    public function sendQrCodeEmail(Request $request)
    {
        $userId = $request->input('userId'); // Sesuaikan dengan kolom ID yang sesuai

        // Dapatkan alamat email pengguna dari model User
        $user = User::find($userId);

        if ($user) {
            $userEmail = $user->email;

            // Generate QR code
            $qrcode = QrCode::size(150)->generate($user->name);
            $qrcodeImage = 'data:image/png;base64,' . base64_encode($qrcode);

            // Kirim email dengan lampiran QR code
            Mail::to($userEmail)->send(new QrCodeMail($qrcodeImage));

            // Respon sukses atau tangani kesalahan jika perlu
            return response()->json(['message' => 'Email sent successfully']);
        } else {
            // Tangani kasus jika pengguna tidak ditemukan
            return response()->json(['message' => 'User not found'], 404);
        }
    }
    public function getUsersData()
    {
        $users = User::leftJoin('roles', 'users.role_id', '=', 'roles.id')
            // ->where('roles.name', '!=', 'admin')
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.jabatan',
                'users.no_telp',
                'roles.name as role_name'
            ])
            ->get();

        return DataTables::of($users)
        ->addColumn('action', function ($user) {
            // Check if ID is not null before generating QR code
            if ($user->id) {
                // $qrcode = QrCode::size(150)->generate($user->name);
                $qrcode = QrCode::size(150)->generate("Pinjam Dulu Seratus");
                $qrcodeImage = 'data:image/png;base64,' . base64_encode($qrcode); // Convert QR code to base64
                return "<div class='d-flex justify-content-between align-items-center'>
                    <a href='#' data-toggle='modal' data-target='#qrcodeModal' data-qrcode='$qrcodeImage'>
                        <button class='btn btn-sm btn-info'>View</button>
                    </a>
                    <a href='" . url('/admin/users/edit/' . $user->id) . "'>
                        <button class='btn btn-sm btn-primary'>Edit</button>
                    </a> <button class='btn btn-sm btn-danger' onclick='deleteUser($user->id)'>Delete</button>
                </div>";

            } else {
                // Handle the case where ID is null (optional)
                return "<span class='text-danger'>ID is null</span>";
            }
        })
        ->rawColumns(['action'])
        ->make(true);
    }

}
