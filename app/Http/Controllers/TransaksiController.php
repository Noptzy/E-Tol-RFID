<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaksi;

class TransaksiController extends Controller
{
    public function prosesTransaksi(Request $request)
    {
        $uid = $request->input('uid');
        $tarif = $request->input('tarif');
<<<<<<< HEAD

        // Simpan UID ke cache
        \Cache::put('latestUID', $uid, 60); // Simpan UID selama 60 detik

        // Cari user berdasarkan UID
        $user = User::where('uid', $uid)->first();

        if (!$user) {
            return response()->json(['status' => 'gagal', 'message' => 'Pengguna tidak ditemukan'], 404);
        }

        if ($user->saldo < $tarif) {
            return response()->json(['status' => 'gagal', 'message' => 'Saldo tidak cukup'], 400);
        }

        // Kurangi saldo
        $user->saldo -= $tarif;
        $user->save();

        // Simpan data transaksi
        $transaksi = Transaksi::create([
            'uid' => $uid,
            'tarif' => $tarif,
            'saldo_akhir' => $user->saldo,
            'waktu_transaksi' => now(),
        ]);

        return response()->json([
            'status' => 'berhasil',
            'saldo' => $user->saldo,
            'uid' => $uid,
            'transaksi' => $transaksi,
        ]);
    }

    public function getUserByUID(Request $request)
    {
        $uid = $request->input('uid'); // UID yang dikirimkan oleh Arduino

        $user = User::where('uid', $uid)->first();

        if (!$user) {
            return response()->json(['status' => 'gagal', 'message' => 'Pengguna tidak ditemukan'], 404);
        }

        return response()->json([
            'status' => 'berhasil',
            'user' => [
                'uid' => $user->uid,
                'nama' => $user->nama,
                'saldo' => $user->saldo,
            ],
        ]);
    }

    public function kurangiSaldo(Request $request)
    {
        $uid = $request->input('uid');
        $tarif = $request->input('tarif');

        $user = User::where('uid', $uid)->first();

        if (!$user) {
            return response()->json(['status' => 'gagal', 'message' => 'Pengguna tidak ditemukan'], 404);
        }

        if ($user->saldo < $tarif) {
            return response()->json(['status' => 'gagal', 'message' => 'Saldo tidak cukup'], 400);
        }

        // Kurangi saldo
        $user->saldo -= $tarif;
        $user->save();

        // Buat transaksi
=======
    
        $user = User::where('uid', $uid)->first();
    
        if (!$user) {
            return response()->json(['status' => 'gagal', 'message' => 'Pengguna tidak ditemukan'], 404);
        }
    
        if ($user->saldo < $tarif) {
            return response()->json(['status' => 'gagal', 'message' => 'Saldo tidak cukup'], 400);
        }
    
        // Kurangi saldo
        $user->saldo -= $tarif;
        $user->save();
    
        // Simpan transaksi
>>>>>>> 92dede23f7465d5cdf94469304be9379de1ab580
        Transaksi::create([
            'uid' => $uid,
            'tarif' => $tarif,
            'saldo_akhir' => $user->saldo,
            'waktu_transaksi' => now(),
        ]);
<<<<<<< HEAD

        return response()->json(['status' => 'berhasil', 'message' => 'Saldo berhasil dikurangi']);
    }
=======
    
        return response()->json([
            'status' => 'berhasil',
            'uid' => $user->uid,
            'nama' => $user->nama,
            'saldo' => $user->saldo,
            'tarif' => $tarif,
        ]);
    }
    
>>>>>>> 92dede23f7465d5cdf94469304be9379de1ab580
}
