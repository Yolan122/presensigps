<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class KategoriController extends Controller
{
    public function index(Request $request)
    {
        $nama_dept = $request->nama_dept;
        $query = Kategori::query();
        $query->select('*');
        if (!empty($nama_dept)) {
            $query->where('nama_dept', 'like', '%' . $nama_dept . '%');
        }
        $kategori = $query->get();
        // $kategori = DB::table('kategori')->orderBy('kode_dept')->get();
        return view('kategori.index', compact('kategori'));
    }


    public function store(Request $request)
    {
        $kode_dept = $request->kode_dept;
        $nama_dept = $request->nama_dept;
        $data = [
            'kode_dept' => $kode_dept,
            'nama_dept' => $nama_dept
        ];

        $cek = DB::table('kategori')->where('kode_dept', $kode_dept)->count();
        if ($cek > 0) {
            return Redirect::back()->with(['warning' => 'Data dengan Kode Dept.' . $kode_dept . ' Sudah Ada']);
        }
        $simpan = DB::table('kategori')->insert($data);
        if ($simpan) {
            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Disimpan']);
        }
    }

    public function edit(Request $request)
    {
        $kode_dept = $request->kode_dept;
        $kategori = DB::table('kategori')->where('kode_dept', $kode_dept)->first();
        return view('kategori.edit', compact('kategori'));
    }

    public function update($kode_dept, Request $request)
    {
        $nama_dept = $request->nama_dept;
        $data = [
            'nama_dept' => $nama_dept
        ];

        $update = DB::table('kategori')->where('kode_dept', $kode_dept)->update($data);
        if ($update) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Update']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Update']);
        }
    }

    public function delete($kode_dept)
    {
        $hapus = DB::table('kategori')->where('kode_dept', $kode_dept)->delete();
        if ($hapus) {
            return Redirect::back()->with(['success' => 'Data Berhasil Di Hapus']);
        } else {
            return Redirect::back()->with(['warning' => 'Data Gagal Di Hapus']);
        }
    }
}
