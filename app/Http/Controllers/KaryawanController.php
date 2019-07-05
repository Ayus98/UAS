<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Karyawan;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = Karyawan::orderBy('created_at', 'DESC')->paginate(10);
        return view('karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        return view('karyawan.add');
    }

    public function save(Request $request)
    {
    //VALIDASI DATA
    $this->validate($request, [
        'kode_karyawan' => 'required|string',
        'nama_karyawan' => 'required|string',
        'phone' => 'required|max:13', //maximum karakter 13 digit
        'address' => 'required|string',
        //unique berarti email ditable karyawans tidak boleh sama
        'email' => 'required|email|string|unique:karyawans,email' // format yag diterima harus email
    ]);

    try {
        $karyawan = Karyawan::create([
            'kode_karyawan' => $request->kode_karyawan,
            'nama_karyawan' => $request->nama_karyawan,
            'phone' => $request->phone,
            'address' => $request->address,
            'email' => $request->email
        ]);
        return redirect('/karyawan')->with(['success' => 'Data telah disimpan']);
    } catch (\Exception $e) {
        return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function edit($id){
    $karyawan = Karyawan::find($id);
    return view('karyawan.edit', compact('karyawan'));
        }

    public function update(Request $request, $id)
{
    $this->validate($request, [
        'kode_karyawan' => 'required|string',
        'nama_karyawan' => 'required|string',
        'phone' => 'required|max:13',
        'address' => 'required|string',
        'email' => 'required|email|string|exists:karyawans,email'
    ]);

    try {
        $karyawan = Karyawan::find($id);
        $karyawan->update([
            'kode_karyawan' => $request->kode_karyawan,
            'nama_karyawan' => $request->nama_karyawan,
            'phone' => $request->phone,
            'address' => $request->address
        ]);
        return redirect('/karyawan')->with(['success' => 'Data telah diperbaharui']);
    } catch (\Exception $e) {
        return redirect()->back()->with(['error' => $e->getMessage()]);
        }
    }

    public function destroy($id)
    {
        $karyawan = Karyawan::find($id);
        $karyawan->delete();
        return redirect()->back()->with(['success' => '<strong>' . $karyawan->name . '</strong> Telah dihapus']);
    }
}
