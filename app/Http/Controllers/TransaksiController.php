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

        $transactionStatus = \Cache::get('transaction_status_' . $uid);

        if ($transactionStatus === 'completed') {
            \Cache::forget('transaction_status_' . $uid); 
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
        $uid = $request->input('uid');

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
                'foto' => $user->foto, 
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

        $user->saldo -= $tarif;
        $user->save();

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
            'saldo_akhir' => $user->saldo 
        ]);
    }
}