<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CabangController;
use App\Http\Controllers\CutiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\HariliburController;
use App\Http\Controllers\IzinabsenController;
use App\Http\Controllers\IzincutiController;
use App\Http\Controllers\IzinsakitController;
use App\Http\Controllers\PenggunaController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\UserController;
use App\Models\Pengguna;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Psy\VarDumper\Presenter;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/




Route::middleware(['guest:pengguna'])->group(function () {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/proseslogin', [AuthController::class, 'proseslogin']);
});


Route::middleware(['guest:user'])->group(function () {
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    Route::post('/prosesloginadmin', [AuthController::class, 'prosesloginadmin']);
});

Route::middleware(['auth:pengguna'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']);
    
    //Presensi
    Route::get('/presensi/{kode_jam_kerja}/create', [PresensiController::class, 'create']);
    Route::get('/presensi/pilihjamkerja', [PresensiController::class, 'pilihjamkerja']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);
    Route::get('/presensi/scanqr', [PresensiController::class, 'scanqr']);
    Route::post('/presensi/storeqr', [PresensiController::class, 'storeqr']);
    //Edit Profile
    Route::get('/editprofile', [PresensiController::class, 'editprofile']);
    Route::post('/presensi/{nik}/updateprofile', [PresensiController::class, 'updateprofile']);

    //Histori
    Route::get('/presensi/histori', [PresensiController::class, 'histori']);
    Route::post('/gethistori', [PresensiController::class, 'gethistori']);

    //Izin
    Route::get('/presensi/izin', [PresensiController::class, 'izin']);
    Route::get('/presensi/buatizin', [PresensiController::class, 'buatizin']);
    Route::post('/presensi/storeizin', [PresensiController::class, 'storeizin']);
    Route::post('/presensi/cekpengajuanizin', [PresensiController::class, 'cekpengajuanizin']);

    //Izin Absen

    Route::get('/izinabsen', [IzinabsenController::class, 'create']);
    Route::post('/izinabsen/store', [IzinabsenController::class, 'store']);
    Route::get('/izinabsen/{kode_izin}/edit', [IzinabsenController::class, 'edit']);
    Route::post('/izinabsen/{kode_izin}/update', [IzinabsenController::class, 'update']);
    //Izin Sakit
    Route::get('/izinsakit', [IzinsakitController::class, 'create']);
    Route::post('/izinsakit/store', [IzinsakitController::class, 'store']);
    Route::get('/izinsakit/{kode_izin}/edit', [IzinsakitController::class, 'edit']);
    Route::post('/izinsakit/{kode_izin}/update', [IzinsakitController::class, 'update']);
    //Izin Cuti
    Route::get('/izincuti', [IzincutiController::class, 'create']);
    Route::post('/izincuti/store', [IzincutiController::class, 'store']);
    Route::get('/izincuti/{kode_izin}/edit', [IzincutiController::class, 'edit']);
    Route::post('/izincuti/{kode_izin}/update', [IzincutiController::class, 'update']);
    Route::post('/izincuti/getmaxcuti', [IzincutiController::class, 'getmaxcuti']);

    Route::get('/izin/{kode_izin}/showact', [PresensiController::class, 'showact']);
    Route::get('/izin/{kode_izin}/delete', [PresensiController::class, 'deleteizin']);
});


//Route Yang Bisa di AKses Oleh Administrator dan Admin Kategori
Route::group(['middleware' => ['role:administrator|admin kategori,user']], function () {
    Route::get('/proseslogoutadmin', [AuthController::class, 'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin', [DashboardController::class, 'dashboardadmin']);

    //Pengguna
    Route::get('/pengguna', [PenggunaController::class, 'index']);
    Route::get('/pengguna/{nik}/resetpassword', [PenggunaController::class, 'resetpassword']);

    //Konfigruasi Jam Kerja
    Route::get('/konfigurasi/{nik}/setjamkerja', [KonfigurasiController::class, 'setjamkerja']);
    Route::post('/konfigurasi/storesetjamkerja', [KonfigurasiController::class, 'storesetjamkerja']);
    Route::post('/konfigurasi/updatesetjamkerja', [KonfigurasiController::class, 'updatesetjamkerja']);
    Route::post('/konfigurasi/storesetjamkerjabydate', [KonfigurasiController::class, 'storesetjamkerjabydate']);
    Route::get('/konfigurasi/{nik}/{bulan}/{tahun}/getjamkerjabydate', [KonfigurasiController::class, 'getjamkerjabydate']);
    Route::post('/konfigurasi/deletejamkerjabydate', [KonfigurasiController::class, 'deletejamkerjabydate']);
    //Presensi
    Route::get('/presensi/monitoring', [PresensiController::class, 'monitoring']);
    Route::post('/getpresensi', [PresensiController::class, 'getpresensi']);
    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanpeta']);
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);

    Route::get('/presensi/izinsakit', [PresensiController::class, 'izinsakit']);

    Route::post('/koreksipresensi', [PresensiController::class, 'koreksipresensi']);
    Route::post('/storekoreksipresensi', [PresensiController::class, 'storekoreksipresensi']);
});


//ROute yang Hanya bisa di akses oleh Administrator
Route::group(['middleware' => ['role:administrator,user']], function () {



    //Pengguna

    Route::post('/pengguna/store', [PenggunaController::class, 'store']);
    Route::post('/pengguna/edit', [PenggunaController::class, 'edit']);
    Route::post('/pengguna/{nik}/update', [PenggunaController::class, 'update']);
    Route::post('/pengguna/{nik}/delete', [PenggunaController::class, 'delete']);
    Route::get('/pengguna/{nik}/lockandunlocklocation', [PenggunaController::class, 'lockandunlocklocation']);
    Route::get('/pengguna/{nik}/lockandunlockjamkerja', [PenggunaController::class, 'lockandunlockjamkerja']);
    //Kategori
    Route::get('/kategori', [KategoriController::class, 'index'])->middleware('permission:view-kategori,user');;
    Route::post('/kategori/store', [KategoriController::class, 'store']);
    Route::post('/kategori/edit', [KategoriController::class, 'edit']);
    Route::post('/kategori/{kode_dept}/update', [KategoriController::class, 'update']);
    Route::post('/kategori/{kode_dept}/delete', [KategoriController::class, 'delete']);

    //Presensi

    Route::post('/presensi/approveizinsakit', [PresensiController::class, 'approveizinsakit']);
    Route::get('/presensi/{kode_izin}/batalkanizinsakit', [PresensiController::class, 'batalkanizinsakit']);


    //Cabang
    Route::get('/cabang', [CabangController::class, 'index']);
    Route::post('/cabang/store', [CabangController::class, 'store']);
    Route::post('/cabang/edit', [CabangController::class, 'edit']);
    Route::post('/cabang/update', [CabangController::class, 'update']);
    Route::post('/cabang/{kode_cabang}/delete', [CabangController::class, 'delete']);

    //Konfigurasi

    Route::get('/konfigurasi/lokasikantor', [KonfigurasiController::class, 'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor', [KonfigurasiController::class, 'updatelokasikantor']);

    Route::get('/konfigurasi/jamkerja', [KonfigurasiController::class, 'jamkerja']);
    Route::post('/konfigurasi/storejamkerja', [KonfigurasiController::class, 'storejamkerja']);
    Route::post('/konfigurasi/editjamkerja', [KonfigurasiController::class, 'editjamkerja']);
    Route::post('/konfigurasi/updatejamkerja', [KonfigurasiController::class, 'updatejamkerja']);

    Route::post('/konfigurasi/jamkerja/{kode_jam_kerja}/delete', [KonfigurasiController::class, 'deletejamkerja']);


    Route::get('/konfigurasi/jamkerjadept', [KonfigurasiController::class, 'jamkerjadept']);
    Route::get('/konfigurasi/jamkerjadept/create', [KonfigurasiController::class, 'createjamkerjadept']);
    Route::post('/konfigurasi/jamkerjadept/store', [KonfigurasiController::class, 'storejamkerjadept']);
    Route::get('/konfigurasi/jamkerjadept/{kode_jk_dept}/edit', [KonfigurasiController::class, 'editjamkerjadept']);
    Route::post('/konfigurasi/jamkerjadept/{kode_jk_dept}/update', [KonfigurasiController::class, 'updatejamkerjadept']);
    Route::get('/konfigurasi/jamkerjadept/{kode_jk_dept}/show', [KonfigurasiController::class, 'showjamkerjadept']);
    Route::get('/konfigurasi/jamkerjadept/{kode_jk_dept}/delete', [KonfigurasiController::class, 'deletejamkerjadept']);

    //User
    Route::get('/konfigurasi/users', [UserController::class, 'index']);
    Route::post('/konfigurasi/users/store', [UserController::class, 'store']);
    Route::post('/konfigurasi/users/edit', [UserController::class, 'edit']);
    Route::post('/konfigurasi/users/{id_user}/update', [UserController::class, 'update']);
    Route::post('/konfigurasi/users/{id_user}/delete', [UserController::class, 'delete']);


    //Harilibur

    Route::get('/konfigurasi/harilibur', [HariliburController::class, 'index']);
    Route::get('/konfigurasi/harilibur/create', [HariliburController::class, 'create']);
    Route::post('/konfigurasi/harilibur/store', [HariliburController::class, 'store']);
    Route::post('/konfigurasi/harilibur/edit', [HariliburController::class, 'edit']);
    Route::post('/konfigurasi/harilibur/{kode_libur}/update', [HariliburController::class, 'update']);
    Route::post('/konfigurasi/harilibur/{kode_libur}/delete', [HariliburController::class, 'delete']);
    Route::get('/konfigurasi/harilibur/{kode_libur}/setpenggunalibur', [HariliburController::class, 'setpenggunalibur']);
    Route::get('/konfigurasi/harilibur/{kode_libur}/setlistpenggunalibur', [HariliburController::class, 'setlistpenggunalibur']);
    Route::get('/konfigurasi/harilibur/{kode_libur}/getsetlistpenggunalibur', [HariliburController::class, 'getsetlistpenggunalibur']);
    Route::post('/konfigurasi/harilibur/storepenggunalibur', [HariliburController::class, 'storepenggunalibur']);
    Route::post('/konfigurasi/harilibur/removepenggunalibur', [HariliburController::class, 'removepenggunalibur']);
    Route::get('/konfigurasi/harilibur/{kode_libur}/getpenggunalibur', [HariliburController::class, 'getpenggunalibur']);
    //Cuti

    Route::get('/cuti', [CutiController::class, 'index']);
    Route::post('/cuti/store', [CutiController::class, 'store']);
    Route::post('/cuti/edit', [CutiController::class, 'edit']);
    Route::post('/cuti/{kode_cuti}/update', [CutiController::class, 'update']);
    Route::post('/cuti/{kode_cuti}/delete', [CutiController::class, 'delete']);
});


Route::get('/createrolepermission', function () {

    try {
        Role::create(['name' => 'admin kategori']);
        // Permission::create(['name' => 'view-pengguna']);
        // Permission::create(['name' => 'view-kategori']);
        echo "Sukses";
    } catch (\Exception $e) {
        echo "Error";
    }
});


Route::get('/give-user-role', function () {
    try {
        $user = User::findorfail(1);
        $user->assignRole('administrator');
        echo "Sukses";
    } catch (\Exception $e) {
        //throw $th;
        echo "Error";
    }
});


Route::get('/give-role-permission', function () {
    try {
        $role = Role::findorfail(1);
        $role->givePermissionTo('view-kategori');
        echo "Sukses";
    } catch (\Exception $e) {
        //throw $th;
        echo "Error";
    }
});