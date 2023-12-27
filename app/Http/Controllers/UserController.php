<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Yajra\DataTables\DataTables;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Mail;
use App\Mail\QrCodeMail;


class UserController extends Controller
{
    public function index(){
        
        return view('Admin.User.index');
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
                return "<a href='#' data-toggle='modal' data-target='#qrcodeModal' data-qrcode='$qrcodeImage'>
                    <button class='btn btn-sm btn-info'>View</button>
                </a>";
            } else {
                // Handle the case where ID is null (optional)
                return "<span class='text-danger'>ID is null</span>";
            }
        })
        ->rawColumns(['action'])
        ->make(true);
    }

}
