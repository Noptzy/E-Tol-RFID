<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaksi;

class TransaksiController extends Controller
{

    public function prosesTransaksi(Request $request)
    {
        $uid = strtoupper($request->input('uid'));
        \Cache::put('latestUID', $uid, 60);

        $user = User::where('uid', $uid)->first();

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'gate_status' => 'closed',
                'message' => 'Pengguna tidak ditemukan'
            ]);
        }

        // Check if there's a completed transaction
        $transactionStatus = \Cache::get('transaction_status_' . $uid);

        if ($transactionStatus === 'completed') {
            \Cache::forget('transaction_status_' . $uid); // Clear the status
            return response()->json([
                'status' => 'success',
                'gate_status' => 'open',
                'message' => 'Transaksi selesai, membuka gerbang'
            ]);
        }

        return response()->json([
            'status' => 'success',
            'gate_status' => 'waiting',
            'message' => 'User terdeteksi',
            'user' => [
                'uid' => $user->uid,
                'nama' => $user->nama,
                'saldo' => $user->saldo
            ]
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
                'foto' => $user->foto, // Pastikan data foto disertakan
            ],
        ]);
    }

    public function kurangiSaldo(Request $request)
    {
        $uid = strtoupper($request->input('uid'));
        $tarif = $request->input('tarif');

        $user = User::where('uid', $uid)->first();

        if (!$user || $user->saldo < $tarif) {
            return response()->json([
                'status' => 'error',
                'gate_status' => 'closed',
                'message' => (!$user) ? 'Pengguna tidak ditemukan' : 'Saldo tidak cukup'
            ]);
        }

        // Kurangi saldo
        $user->saldo -= $tarif;
        $user->save();

        // Buat transaksi
        Transaksi::create([
            'uid' => $uid,
            'tarif' => $tarif,
            'saldo_akhir' => $user->saldo,
            'waktu_transaksi' => now()
        ]);

        \Cache::put('transaction_status_' . $uid, 'completed', 60);

        return response()->json([
            'status' => 'success',
            'gate_status' => 'open',
            'message' => 'Saldo berhasil dikurangi',
            'saldo_akhir' => $user->saldo // Add this line
        ]);
    }
}