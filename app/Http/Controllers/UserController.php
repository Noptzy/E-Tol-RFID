<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Storage;

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
        try {
            $validatedData = $request->validate([
                'uid' => 'required|unique:users',
                'nama' => 'required',
                'saldo' => 'required|numeric',
                'foto' => 'image|mimes:jpeg,png,jpg|max:2048'
            ]);

            if ($request->hasFile('foto')) {
                $foto = $request->file('foto');
                $filename = time() . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('public/fotos', $filename);
                $validatedData['foto'] = $filename;
            }

            User::create($validatedData);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil ditambahkan'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('users.edit', compact('user'));
    }
    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);
            
            $validatedData = $request->validate([
                'uid' => 'required|unique:users,uid,' . $id,
                'nama' => 'required',
                'saldo' => 'required|numeric',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048'
            ]);
    
            if ($request->hasFile('foto')) {
                if ($user->foto) {
                    Storage::delete('public/fotos/' . $user->foto);
                }
                
                $foto = $request->file('foto');
                $filename = time() . '.' . $foto->getClientOriginalExtension();
                $foto->storeAs('public/fotos', $filename);
                $validatedData['foto'] = $filename;
            }
    
            $user->update($validatedData);
    
            return response()->json([
                'success' => true,
                'message' => 'User berhasil diupdate'
            ], 200);
    
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        return redirect()->route('users.index')->with('success', 'User berhasil dihapus.');
    }
}
