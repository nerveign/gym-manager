<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GymClass;
use App\Models\User;
use Illuminate\Http\Request;

class ClassController extends Controller
{
  /**
   * Menampilkan Form Tambah Kelas
   */
  public function create()
  {
    // Ambil daftar trainer untuk dropdown
    $trainers = User::where('role', 'trainer')->get();
    return view('admin.class-create', compact('trainers'));
  }

  /**
   * Menyimpan Kelas Baru
   */
  public function store(Request $request)
  {
    $request->validate([
      'type' => 'required|string|max:255',
      'trainer_id' => 'required|exists:users,id',
      'schedule' => 'required|date',
      'capacity' => 'required|integer|min:1',
      'description' => 'nullable|string',
    ]);

    GymClass::create([
      'type' => $request->type,
      'trainer_id' => $request->trainer_id,
      'schedule' => $request->schedule,
      'capacity' => $request->capacity,
      'description' => $request->description,
    ]);

    return redirect()->route('admin.classes_management')
      ->with('success', 'Kelas berhasil dibuat!');
  }

  /**
   * Menampilkan Form Edit Kelas
   */
  public function edit($id)
  {
    $gymClass = GymClass::findOrFail($id);
    $trainers = User::where('role', 'trainer')->get();

    return view('admin.class-edit', compact('gymClass', 'trainers'));
  }

  /**
   * Mengupdate Data Kelas
   */
  public function update(Request $request, $id)
  {
    $request->validate([
      'type' => 'required|string|max:255',
      'trainer_id' => 'required|exists:users,id',
      'schedule' => 'required|date',
      'capacity' => 'required|integer|min:1',
      'description' => 'nullable|string',
    ]);

    $gymClass = GymClass::findOrFail($id);

    $gymClass->update([
      'type' => $request->type,
      'trainer_id' => $request->trainer_id,
      'schedule' => $request->schedule,
      'capacity' => $request->capacity,
      'description' => $request->description,
    ]);

    return redirect()->route('admin.classes_management')
      ->with('success', 'Kelas berhasil diperbarui!');
  }

  /**
   * Menghapus Kelas
   */
  public function destroy($id)
  {
    $gymClass = GymClass::findOrFail($id);
    $gymClass->delete();

    return redirect()->route('admin.classes_management')
      ->with('success', 'Kelas berhasil dihapus!');
  }
}
