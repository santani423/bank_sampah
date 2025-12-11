<?php

use App\Http\Controllers\ActivityController;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
// Admin Controller
use App\Http\Controllers\Admin\NasabahController as AdminNasabahController;
use App\Http\Controllers\Admin\PetugasController as AdminPetugasController;
use App\Http\Controllers\Admin\SampahController as AdminSampahController;
use App\Http\Controllers\Admin\PengepulController as AdminPengepulController;
use App\Http\Controllers\Admin\BannerController as AdminBannerController;
use App\Http\Controllers\Admin\ArtikelController as AdminArtikelController;
use App\Http\Controllers\Admin\AplikasiAndroidController as AdminAplikasiAndroidController;
use App\Http\Controllers\Admin\TokenWhatsAppController as AdminTokenWhatsAppController;
use App\Http\Controllers\Admin\FeedbackController as AdminFeedbackController;
use App\Http\Controllers\Admin\TentangKamiController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\PencairanSaldoController as AdminTarikSaldoController;
use App\Http\Controllers\Admin\PengirimanPengepulController as AdminPengirimanPengepulController;
use App\Http\Controllers\Admin\TransaksiController as AdminTransaksiController;
use App\Http\Controllers\Admin\LaporanController as AdminLaporanController;
use App\Http\Controllers\Admin\AdminCabangController;
use App\Http\Controllers\Admin\SettingController;
use App\Http\Controllers\Admin\TopUpController as AdminTopUpController;
use App\Http\Controllers\Admin\LapakController as AdminLapakController;
use App\Http\Controllers\Api\LapakTransaksiController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\landingPageController;
use App\Http\Controllers\CleanController;
use App\Http\Controllers\GudangController;
// Petugas Controller
use App\Http\Controllers\Petugas\DashboardController as PetugasDashboardController;
use App\Http\Controllers\Petugas\TransaksiController as PetugasTransaksiController;
use App\Http\Controllers\Petugas\NasabahController as PetugasNasabahController;
use App\Http\Controllers\Petugas\LapakController as PetugasLapakController;
use App\Http\Controllers\TessController;

use App\Http\Controllers\Nasabah\DashboardController as NasabahDashboardController;
use App\Http\Controllers\Nasabah\NasabahTransaksiController as NasabahTransaksiController;
use App\Http\Controllers\Nasabah\CabangController as NasabahCabangController;
use App\Http\Controllers\Nasabah\MetodePenarikanController as NasabahMetodePenarikanController;
use App\Http\Controllers\Petugas\NasabahUserBadanController;
use App\Http\Controllers\Petugas\LapakController;
use App\Http\Controllers\TimeController;
use Illuminate\Support\Facades\Response;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/



// Route::controller(landingPageController::class)->group(function () {
//     Route::get('/', 'index')->name('home');
//     Route::get('kegiatan', 'kegiatan')->name('kegiatan');
//     Route::get('about', 'about')->name('about');
//     Route::get('berita', 'berita')->name('berita');
//     Route::get('berita/{id}', 'beritaDetail')->name('berita.detail');
//     Route::get('berita/kategori/{id}', 'beritaKategori')->name('berita.kategori');
// });


Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('home');
    Route::get('login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [AuthController::class, 'login'])->name('login.post');

    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('register');
    Route::post('register', [AuthController::class, 'register'])->name('register.post');
});

Route::middleware(['auth', 'checkRole:admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/facek-user', [AdminDashboardController::class, 'faceUser'])->name('admin.faceUser');

    // Data Master
    Route::resource('/data-nasabah', AdminNasabahController::class)->names('admin.nasabah');
    Route::resource('/data-petugas', AdminPetugasController::class)->names('admin.petugas');
    Route::post('/data-petugas/{id}/alokasi-saldo', [AdminPetugasController::class, 'alokasiSaldo'])->name('admin.petugas.alokasiSaldo');
    Route::resource('/data-sampah', AdminSampahController::class)->names('admin.sampah');
    Route::resource('/data-pengepul', AdminPengepulController::class)->names('admin.pengepul');
    Route::resource('/data-collaction-center', AdminCabangController::class)->names('admin.cabang');
    Route::resource('/data-tim', TimeController::class)->names('admin.time');
    Route::resource('/data-label', LabelController::class)->names('admin.labels');
    Route::resource('/data-activities', ActivityController::class)->names('admin.activities');
    Route::resource('/data-customers', GudangController::class)->names('admin.gudangs');
    Route::post('admin/customers/import', [GudangController::class, 'import'])->name('admin.gudangs.import');

    // Approval Setoran Lapak
    Route::get('/setor-lapak', [AdminLapakController::class, 'approvalSetoranLapak'])->name('admin.setor-lapak.index');
    Route::get('/setor-lapak/{code}', [AdminLapakController::class, 'approvalSetoranLapakDetail'])->name('admin.setor-lapak.detail');
    // Data Clean
    Route::resource('data-cleans', CleanController::class)->names('admin.cleans');

    // Lapak Management
    Route::resource('/data-lapak', AdminLapakController::class)->names('admin.lapak');
    Route::post('/data-lapak/{id}/approve', [AdminLapakController::class, 'approve'])->name('admin.lapak.approve');
    Route::post('/data-lapak/{id}/reject', [AdminLapakController::class, 'reject'])->name('admin.lapak.reject');
    Route::post('/data-collaction-center/update-anggota-cabang', [AdminCabangController::class, 'updateAanggotaCabang'])->name('admin.cabang.updateAanggotaCabang');

    // Manajemen Konten
    Route::resource('/data-banner', AdminBannerController::class)->names('admin.banner');
    Route::resource('/data-artikel', AdminArtikelController::class)->names('admin.artikel');

    // Transaksi
    Route::resource('/transaksi', AdminTransaksiController::class)->names('admin.transaksi');
    Route::get('/transaksi/print/{transaksi}', [AdminTransaksiController::class, 'print'])->name('admin.transaksi.print');



    Route::get('/laporan', [AdminLaporanController::class, 'index'])->name('admin.laporan.index');
    Route::get('/laporan/print', [AdminLaporanController::class, 'print'])->name('admin.laporan.print');
    // Route::get('', 'AdminTransaksiController@print')->name('admin.transaksi.print');

    Route::get('/tarik-saldo', [AdminTarikSaldoController::class, 'index'])->name('admin.tarik-saldo.index');
    Route::post('/tarik-saldo/setujui/{id}', [AdminTarikSaldoController::class, 'setujui'])->name('admin.tarik-saldo.setujui');
    Route::post('/tarik-saldo/tolak/{id}', [AdminTarikSaldoController::class, 'tolak'])->name('admin.tarik-saldo.tolak');

    Route::resource('/pengiriman/sampah', AdminPengirimanPengepulController::class)->names('admin.pengiriman');

    // Pengaturan
    Route::get('/token-whatsapp', [AdminTokenWhatsAppController::class, 'index'])->name('admin.token-whatsapp.index');
    Route::post('/token-whatsapp', [AdminTokenWhatsAppController::class, 'update'])->name('admin.token-whatsapp.update');
    Route::resource('/update-apk', AdminAplikasiAndroidController::class)->names('admin.aplikasi');
    Route::get('/tentang-kami', [TentangKamiController::class, 'index'])->name('admin.tentang_kami.index');
    Route::post('/tentang-kami', [TentangKamiController::class, 'store'])->name('admin.tentang_kami.store');
    Route::put('/tentang-kami/update/{id}', [TentangKamiController::class, 'update'])->name('admin.tentang_kami.update');

    // Feedback
    Route::resource('/data-feedback', AdminFeedbackController::class)->names('admin.feedback');
    Route::resource('/settings', SettingController::class)->names('admin.settings');

    // Top Up Saldo Utama
    Route::get('/topup', [AdminTopUpController::class, 'index'])->name('admin.topup.index');
    Route::get('/topup/create', [AdminTopUpController::class, 'create'])->name('admin.topup.create');
    Route::post('/topup/store', [AdminTopUpController::class, 'store'])->name('admin.topup.store');
    Route::get('/topup/success', [AdminTopUpController::class, 'success'])->name('admin.topup.success');
    Route::get('/topup/{id}', [AdminTopUpController::class, 'show'])->name('admin.topup.show');


    
    Route::get('/lapak/transaksi/{id}/download', [LapakController::class, 'downloadTransaksi'])->name('admin.lapak.transaksi.download');
});

Route::middleware(['auth', 'checkRole:petugas'])->prefix('petugas')->group(function () {
        Route::post('/data-lapak/{lapak}/kirim-sampah', [PetugasLapakController::class, 'prosesKirimSampah'])->name('petugas.lapak.proses-kirim-sampah');
    Route::get('/dashboard', [PetugasDashboardController::class, 'index'])->name('petugas.dashboard');

    // Data Master
    Route::resource('/data-nasabah', PetugasNasabahController::class)->names('petugas.nasabah');
    Route::resource('/data-rekanan', NasabahUserBadanController::class)->names('petugas.rekanan');
    Route::resource('/data-lapak', PetugasLapakController::class)->names('petugas.lapak');
    Route::get('/data-lapak/{lapak}/kirim-sampah', [PetugasLapakController::class, 'kirimSampah'])->name('petugas.lapak.kirim-sampah');
    Route::get('/data-lapak/{lapak}/setor-sampah', [PetugasLapakController::class, 'setorSampah'])->name('petugas.lapak.setor-sampah');
    Route::post('/data-lapak/{lapak}/setor-sampah', [PetugasLapakController::class, 'storeSetorSampah'])->name('petugas.lapak.setor-sampah.store');
    Route::get('/data-rekanan/{nasabahBadan}/setor-sampah', [NasabahUserBadanController::class, 'setorSampah'])->name('petugas.rekanan.setor-sampah');
    Route::post('/data-rekanan/{nasabahBadan}/setor-sampah', [NasabahUserBadanController::class, 'storeSetorSampah'])->name('petugas.rekanan.setor-sampah.store');
    // Import data sampah (Excel/CSV)
    Route::post('/data-rekanan/{nasabahBadan}/sampah-import', [NasabahUserBadanController::class, 'importSampah'])->name('petugas.rekanan.sampah-import');
    Route::get('/data-rekanan/{nasabahBadan}/transaksi/{transaksi}', [NasabahUserBadanController::class, 'showTransaksi'])->name('petugas.rekanan.transaksi.show');
    Route::get('/data-rekanan/{nasabahBadan}/transaksi/{transaksi}/print', [NasabahUserBadanController::class, 'printTransaksi'])->name('petugas.rekanan.transaksi.print');
    // Transaksi
    Route::resource('/transaksi', PetugasTransaksiController::class)->names('petugas.transaksi');
    Route::get('/transaksi/print/{transaksi}', [PetugasTransaksiController::class, 'print'])->name('petugas.transaksi.print');
    // Route::get('/transaksi/top-up/saldo', [PetugasTransaksiController::class, 'topUp'])->name('petugas.transaksi.top-up');
    Route::post('/midtrans/token', [PetugasTransaksiController::class, 'createTransaction'])->name('bayar.proses');
    Route::post('/midtrans/notification', [PetugasTransaksiController::class, 'handleNotification']);
    Route::post('/midtrans/callback', [PetugasTransaksiController::class, 'callback']);
    Route::resource('/pengiriman/sampah', AdminPengirimanPengepulController::class)->names('petugas.pengiriman');

    Route::get('/lapak/transaksi/{id}', [LapakController::class, 'showTransaksi'])->name('petugas.lapak.transaksi.detail');
    Route::get('/lapak/transaksi/ready-to-ship/{id}', [LapakController::class, 'readyToShip'])->name('petugas.lapak.transaksi.ready-to-ship');
    Route::get('/lapak/transaksi/{id}/download', [LapakController::class, 'downloadTransaksi'])->name('petugas.lapak.transaksi.download');
});

Route::middleware(['auth', 'checkRole:nasabah'])->prefix('nasabah')->group(function () {
    Route::get('/dashboard', [NasabahDashboardController::class, 'index'])->name('nasabah.dashboard');
    Route::get('/profile', [NasabahDashboardController::class, 'profile'])->name('nasabah.profile');
    Route::put('/update/{id}', [NasabahDashboardController::class, 'update'])->name('nasabah.update');

    // Data Cabang
    Route::resource('/cabang', NasabahCabangController::class)->names('nasabah.cabang');
    // Transaksi
    Route::resource('/transaksi', NasabahTransaksiController::class)->names('nasabah.transaksi')->except(['show']);
    Route::get('/transaksi/setoran', [NasabahTransaksiController::class, 'setoran'])->name('nasabah.transaksi.setoran');
    Route::get('/transaksi/penarikan', [NasabahTransaksiController::class, 'penarikan'])->name('nasabah.transaksi.penarikan');
    Route::get('/transaksi/print/{transaksi}', [PetugasTransaksiController::class, 'print'])->name('nasabah.transaksi.print');

    Route::resource('/metode-penarikan', NasabahMetodePenarikanController::class)->names('nasabah.metode-penarikan');
});

Route::post('logout', [AuthController::class, 'logout'])->name('logout');

// Xendit Callback (tidak perlu auth)
Route::post('/xendit/callback/topup', [AdminTopUpController::class, 'callback'])->name('xendit.callback.topup');




// Halaman testing TessController
Route::get('/testing', [TessController::class, 'testing'])->name('testing');

// Route proses top up Xendit
Route::post('/topup/proses', [TessController::class, 'proses'])->name('topup.proses');

Route::get('storage/{filename}', function ($filename) {
    $path = storage_path('app/public/' . $filename);

    if (!file_exists($path)) {
        abort(404);
    }

    return Response::file($path);
});
