<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'uid' => 'required|unique:users,uid',
            'saldo' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $namaFile = strtolower(str_replace(' ', '', $request->nama)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('users', $namaFile, 'public');
        }

        User::create([
            'nama' => $request->nama,
            'uid' => $request->uid,
            'saldo' => $request->saldo,
            'foto' => $path,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $request->validate([
            'nama' => 'required|string|max:255',
            'saldo' => 'required|integer',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $path = $user->foto;

        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($user->foto) {
                Storage::delete('public/' . $user->foto);
            }

            $file = $request->file('foto');
            $namaFile = strtolower(str_replace(' ', '', $request->nama)) . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('users', $namaFile, 'public');
        }

        $user->update([
            'nama' => $request->nama,
            'saldo' => $request->saldo,
            'foto' => $path,
        ]);

        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    public function show($id)
    {
        $user = User::findOrFail($id);
        return view('users.show', compact('user'));
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);

        if ($user->foto) {
            Storage::delete('public/' . $user->foto);
        }

        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
