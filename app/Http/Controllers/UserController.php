<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    // Tampilkan semua user
    public function index()
    {
        $users = User::all();
        return view('users.index', compact('users'));
    }

    // Form tambah user
    public function create()
    {
        return view('users.create');
    }

    // Simpan user baru
    public function store(Request $request)
    {
        $validated = $request->validate([
            'uid' => 'required|unique:users',
            'nama' => 'required|string|max:255',
            'saldo' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Cek apakah file foto diunggah
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/fotos', $fileName); // Simpan ke folder 'storage/app/public/fotos'
            $validated['foto'] = $fileName; // Simpan nama file ke database
        } else {
            $validated['foto'] = 'default.jpg'; // Berikan nilai default jika tidak ada foto yang diunggah
        }

        User::create($validated);
        return redirect()->route('users.index')->with('success', 'User berhasil ditambahkan.');
    }


    // Form edit user
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }

    // Update user
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'uid' => 'required|unique:users,uid,' . $id,
            'nama' => 'required|string|max:255',
            'saldo' => 'required|numeric',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
    
        $user = User::findOrFail($id);
    
        // Cek apakah file foto diunggah
        if ($request->hasFile('foto')) {
            $file = $request->file('foto');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/fotos', $fileName); // Simpan ke folder 'storage/app/public/fotos'
            $validated['foto'] = $fileName; // Simpan nama file ke database
        } else {
            $validated['foto'] = $user->foto; // Pertahankan nilai foto yang ada jika tidak ada foto baru yang diunggah
        }
    
        $user->update($validated);
        return redirect()->route('users.index')->with('success', 'User berhasil diperbarui.');
    }

    // Hapus user
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
