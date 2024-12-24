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
        Transaksi::create([
            'uid' => $uid,
            'tarif' => $tarif,
            'saldo_akhir' => $user->saldo,
            'waktu_transaksi' => now(),
        ]);
    
        return response()->json([
            'status' => 'berhasil',
            'uid' => $user->uid,
            'nama' => $user->nama,
            'saldo' => $user->saldo,
            'tarif' => $tarif,
        ]);
    }
    
}
