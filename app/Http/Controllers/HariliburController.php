<?php

namespace App\Http\Controllers;

use App\Models\Harilibur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class HariliburController extends Controller
{
    public function index()
    {

        $query = Harilibur::query();
        $query->orderBy('kode_libur', 'desc');
        $harilibur = $query->paginate(10);
        return view('harilibur.index', compact('harilibur'));
    }

    public function create()
    {
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('harilibur.create', compact('cabang'));
    }

    public function store(Request $request)
    {
        //LB24001
        $tahun = date('Y', strtotime($request->tanggal_libur));
        $thn = substr($tahun, 2, 2);
        $lastlibur = DB::table('harilibur')
            ->whereRaw('YEAR(tanggal_libur)="' . $tahun . '"')
            ->orderBy('kode_libur', 'desc')
            ->first();
        $lastkodelibur = $lastlibur != null ? $lastlibur->kode_libur : "";
        $format = "LB"  . $thn;
        $kode_libur = buatkode($lastkodelibur, $format, 3);

        try {
            DB::table('harilibur')
                ->insert([
                    'kode_libur' => $kode_libur,
                    'tanggal_libur' => $request->tanggal_libur,
                    'kode_cabang' => $request->kode_cabang,
                    'keterangan' => $request->keterangan
                ]);

            return Redirect::back()->with(['success' => 'Data Berhasil Disimpan']);
        } catch (\Exception $e) {
            //dd($e);
            return Redirect::back()->with(['warning' => $e->getMessage()]);
        }
    }


    public function edit(Request $request)
    {
        $kode_libur = $request->kode_libur;
        $harilibur = DB::table('harilibur')->where('kode_libur', $kode_libur)->first();
        $cabang = DB::table('cabang')->orderBy('kode_cabang')->get();
        return view('harilibur.edit', compact('cabang', 'harilibur'));
    }


    public function update(Request $request, $kode_libur)
    {
        try {
            DB::table('harilibur')
                ->where('kode_libur', $kode_libur)
                ->update([
                    'tanggal_libur' => $request->tanggal_libur,
                    'kode_cabang' => $request->kode_cabang,
                    'keterangan' => $request->keterangan
                ]);

            return Redirect::back()->with(['success' => 'Data Berhasil Diupdate']);
        } catch (\Exception $e) {
            //dd($e);
            return Redirect::back()->with(['warning' => $e->getMessage()]);
        }
    }

    public function delete($kode_libur)
    {
        try {
            DB::table('harilibur')->where('kode_libur', $kode_libur)->delete();
            return Redirect::back()->with(['success' => 'Data Berhasil Dihapus']);
        } catch (\Exception $e) {
            return Redirect::back()->with(['warning' => $e->getMessage()]);
        }
    }


    public function setpenggunalibur($kode_libur)
    {
        $harilibur = DB::table('harilibur')
            ->join('cabang', 'harilibur.kode_cabang', '=', 'cabang.kode_cabang')
            ->where('kode_libur', $kode_libur)->first();
        return view('harilibur.setpenggunalibur', compact('harilibur'));
    }


    public function setlistpenggunalibur($kode_libur)
    {
        return view('harilibur.setlistpenggunalibur', compact('kode_libur'));
    }


    public function getsetlistpenggunalibur($kode_libur)
    {
        $harilibur = DB::table('harilibur')->where('kode_libur', $kode_libur)->first();
        $pengguna = DB::table('pengguna')
            ->select('pengguna.*', 'hariliburdetail.nik as ceknik')
            ->leftJoin(
                DB::raw("(
                SELECT nik FROM harilibur_detail
                WHERE kode_libur = '$kode_libur'
            ) hariliburdetail"),
                function ($join) {
                    $join->on('pengguna.nik', '=', 'hariliburdetail.nik');
                }
            )
            ->where('kode_cabang', $harilibur->kode_cabang)
            ->orderBy('nama_lengkap')
            ->get();
        return view('harilibur.getsetlistpenggunalibur', compact('pengguna', 'kode_libur'));
    }


    public function storepenggunalibur(Request $request)
    {
        try {
            $cek = DB::table('harilibur_detail')
                ->where('kode_libur', $request->kode_libur)
                ->where('nik', $request->nik)->count();
            if ($cek > 0) {
                return 1;
            }
            DB::table('harilibur_detail')->insert([
                'kode_libur' => $request->kode_libur,
                'nik' => $request->nik,
            ]);
            return 0;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function removepenggunalibur(Request $request)
    {
        try {
            DB::table('harilibur_detail')
                ->where('kode_libur', $request->kode_libur)
                ->where('nik', $request->nik)
                ->delete();
            return 0;
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }


    public function getpenggunalibur($kode_libur)
    {
        $penggunalibur = DB::table('harilibur_detail')
            ->join('pengguna', 'harilibur_detail.nik', '=', 'pengguna.nik')
            ->where('kode_libur', $kode_libur)
            ->get();

        return view('harilibur.getpenggunalibur', compact('penggunalibur', 'kode_libur'));
    }
}
