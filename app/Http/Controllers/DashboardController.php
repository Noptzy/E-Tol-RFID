<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        return view('dashboard');
    }

    public function findUser(Request $request)
    {
        $uid = $request->input('uid');
        $user = User::where('uid', $uid)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Pengguna tidak ditemukan!'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $user]);
    }
    
    public function deductSaldo(Request $request)
    {
        $request->validate([
            'uid' => 'required',
            'amount' => 'required|numeric|min:1',
        ]);

        $user = User::where('uid', $request->input('uid'))->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Pengguna tidak ditemukan!'], 404);
        }

        $amount = $request->input('amount');
        if ($user->saldo < $amount) {
            return response()->json(['status' => 'error', 'message' => 'Saldo tidak mencukupi!'], 400);
        }

        $user->saldo -= $amount;
        $user->save();

        return response()->json(['status' => 'success', 'message' => 'Saldo berhasil dikurangi!', 'data' => $user]);
    }

    public function findUserAjax(Request $request)
    {
        $uid = $request->input('uid');
        $user = User::where('uid', $uid)->first();

        if (!$user) {
            return response()->json(['status' => 'error', 'message' => 'Pengguna tidak ditemukan!'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $user]);
    }

}
