<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Utilitas;
use App\Models\AirBersih;
use App\Models\AirHujan;
use App\Models\ListrikMdp;
use App\Models\ListrikSdp;
use App\Models\ListrikLift;
use App\Models\ListrikAc;
use App\Models\ListrikLampu;

class UtilitasController extends Controller
{
    // Method untuk menampilkan form
    public function create()
    {
        return view('admin.utilitas.create');
    }

    // Method untuk memproses data dari form dinamis
    public function store(Request $request)
    {
        // 1. Validasi Input Dasar
        $request->validate([
            'jenis_utilitas' => 'required|in:AirBersih,AirHujan,MDP,SDP,Lift,AC,Lampu',
            'periode'        => 'required|date_format:Y-m',
            'tgl_awal'       => 'required|date',
            'tgl_akhir'      => 'required|date|after_or_equal:tgl_awal',
        ]);

        try {
            DB::transaction(function () use ($request) {

                // 2. Simpan ke Tabel Induk (Utilitas)
                $utilitas = Utilitas::create([
                    'petugas_id'     => auth()->id(),
                    'jenis_utilitas' => $request->jenis_utilitas,
                    'periode'        => $request->periode,
                ]);

                $id = $utilitas->id;
                $jenis = $request->jenis_utilitas;

                // 3. Simpan ke Tabel Turunan berdasarkan 'Jenis Utilitas'

                // KELOMPOK 1: Single Input (Air & MDP)
                if (in_array($jenis, ['AirBersih', 'AirHujan', 'MDP'])) {
                    $modelClass = match ($jenis) {
                        'AirBersih' => AirBersih::class,
                        'AirHujan'  => AirHujan::class,
                        'MDP'       => ListrikMdp::class,
                    };

                    $modelClass::create([
                        'id'          => $id,
                        'tgl_awal'    => $request->tgl_awal,
                        'tgl_akhir'   => $request->tgl_akhir,
                        'stand_awal'  => $request->stand_awal,
                        'stand_akhir' => $request->stand_akhir,
                    ]);
                }

                // KELOMPOK 2: Double Input (SDP & Lift)
                elseif (in_array($jenis, ['SDP', 'Lift'])) {
                    $modelClass = match ($jenis) {
                        'SDP'  => ListrikSdp::class,
                        'Lift' => ListrikLift::class,
                    };

                    // Prefix kolom database berbeda antara SDP dan Lift
                    $p1 = $jenis === 'Lift' ? '_g' : '_sdp1';
                    $p2 = $jenis === 'Lift' ? '_g2' : '_sdp2';

                    $modelClass::create([
                        'id'          => $id,
                        'tgl_awal'    => $request->tgl_awal,
                        'tgl_akhir'   => $request->tgl_akhir,
                        'stand_awal' . $p1  => $request->stand_awal_1,
                        'stand_akhir' . $p1  => $request->stand_akhir_1,
                        'stand_awal' . $p2  => $request->stand_awal_2,
                        'stand_akhir' . $p2  => $request->stand_akhir_2,
                    ]);
                }

                // KELOMPOK 3: Triple Input (AC & Lampu)
                elseif (in_array($jenis, ['AC', 'Lampu'])) {
                    $modelClass = match ($jenis) {
                        'AC'    => ListrikAc::class,
                        'Lampu' => ListrikLampu::class,
                    };

                    $modelClass::create([
                        'id'             => $id,
                        'tgl_awal'       => $request->tgl_awal,
                        'tgl_akhir'      => $request->tgl_akhir,
                        'stand_awal_l1'  => $request->stand_awal_l1,
                        'stand_akhir_l1' => $request->stand_akhir_l1,
                        'stand_awal_l2'  => $request->stand_awal_l2,
                        'stand_akhir_l2' => $request->stand_akhir_l2,
                        'stand_awal_l3'  => $request->stand_awal_l3,
                        'stand_akhir_l3' => $request->stand_akhir_l3,
                    ]);
                }
            });

            // Redirect kembali ke halaman form dengan pesan sukses
            return redirect()->route('admin.utilitas.create')->with('success', 'Data utilitas berhasil disimpan!');
        } catch (\Exception $e) {
            // Tangkap jika terjadi error database
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $utilitas = \App\Models\Utilitas::findOrFail($id);

        // Tentukan relasi berdasarkan jenis_utilitas untuk eager loading
        $relation = match ($utilitas->jenis_utilitas) {
            'AirBersih' => 'airBersih',
            'AirHujan' => 'airHujan',
            'MDP' => 'listrikMdp',
            'SDP' => 'listrikSdp',
            'Lift' => 'listrikLift',
            'AC' => 'listrikAc',
            'Lampu' => 'listrikLampu',
            default => null
        };

        if ($relation) {
            $utilitas->load($relation);
        }

        return response()->json($utilitas);
    }

    public function update(Request $request, $id)
    {
        $utilitas = \App\Models\Utilitas::findOrFail($id);

        return DB::transaction(function () use ($request, $utilitas) {
            // 1. Update Tabel Induk
            $utilitas->update([
                'periode' => $request->periode,
            ]);

            // 2. Update Tabel Turunan berdasarkan jenis
            switch ($utilitas->jenis_utilitas) {
                case 'AirBersih':
                case 'AirHujan':
                case 'MDP':
                    $relation = strtolower($utilitas->jenis_utilitas == 'MDP' ? 'listrikMdp' : $utilitas->jenis_utilitas);
                    $utilitas->$relation()->update($request->only(['tgl_awal', 'tgl_akhir', 'stand_awal', 'stand_akhir']));
                    break;
                case 'SDP':
                    $utilitas->listrikSdp()->update($request->only(['tgl_awal', 'tgl_akhir', 'stand_awal_sdp1', 'stand_awal_sdp2', 'stand_akhir_sdp1', 'stand_akhir_sdp2']));
                    break;
                case 'Lift':
                    $utilitas->listrikLift()->update($request->only(['tgl_awal', 'tgl_akhir', 'stand_awal_g', 'stand_awal_g2', 'stand_akhir_g', 'stand_akhir_g2']));
                    break;
                case 'AC':
                case 'Lampu':
                    $relation = $utilitas->jenis_utilitas == 'AC' ? 'listrikAc' : 'listrikLampu';
                    $utilitas->$relation()->update($request->only(['tgl_awal', 'tgl_akhir', 'stand_awal_l1', 'stand_awal_l2', 'stand_awal_l3', 'stand_akhir_l1', 'stand_akhir_l2', 'stand_akhir_l3']));
                    break;
            }

            return redirect()->route('admin.utilitas.index')->with('success', 'Data utilitas berhasil diperbarui.');
        });
    }

    public function destroy($id)
    {
        $utilitas = \App\Models\Utilitas::findOrFail($id);
        // Karena di migration menggunakan cascadeOnDelete, data di tabel turunan otomatis terhapus
        $utilitas->delete();

        return redirect()->route('admin.utilitas.index')->with('success', 'Data utilitas berhasil dihapus.');
    }

    public function showDetail(Request $request, $jenis)
    {
        $tahun = $request->query('tahun', date('Y'));

        // Ambil data utilitas berdasarkan jenis dan tahun
        $dataUtilitas = \App\Models\Utilitas::with(['airBersih', 'airHujan', 'listrikMdp', 'listrikSdp', 'listrikLift', 'listrikAc', 'listrikLampu'])
            ->where('jenis_utilitas', $jenis)
            ->where('periode', 'like', "$tahun-%")
            ->get();

        // Inisialisasi data 12 bulan (Jan - Des)
        $labels = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $consumptions = array_fill(0, 12, 0);

        foreach ($dataUtilitas as $util) {
            // Ambil bulan dari format YYYY-MM (index 5, panjang 2)
            $bulanIndex = (int) substr($util->periode, 5, 2) - 1;
            if ($bulanIndex >= 0 && $bulanIndex < 12) {
                $consumptions[$bulanIndex] += $util->total_konsumsi; // Menggunakan accessor total_konsumsi dari model Utilitas
            }
        }

        return view('admin.utilitas.detail', compact('jenis', 'tahun', 'labels', 'consumptions', 'dataUtilitas'));
    }

    public function index()
    {
        // Daftar 7 jenis utilitas secara statis
        $jenisUtilitas = [
            ['nama' => 'Air Bersih', 'slug' => 'AirBersih', 'icon' => 'droplet', 'warna' => 'blue'],
            ['nama' => 'Air Hujan', 'slug' => 'AirHujan', 'icon' => 'cloud-rain', 'warna' => 'cyan'],
            ['nama' => 'Listrik MDP', 'slug' => 'MDP', 'icon' => 'bolt', 'warna' => 'amber'],
            ['nama' => 'Listrik SDP', 'slug' => 'SDP', 'icon' => 'zap', 'warna' => 'yellow'],
            ['nama' => 'Listrik Lift', 'slug' => 'Lift', 'icon' => 'arrow-up-down', 'warna' => 'orange'],
            ['nama' => 'Listrik AC', 'slug' => 'AC', 'icon' => 'wind', 'warna' => 'indigo'],
            ['nama' => 'Listrik Lampu', 'slug' => 'Lampu', 'icon' => 'lightbulb', 'warna' => 'emerald'],
        ];

        return view('admin.utilitas.index', compact('jenisUtilitas'));
    }

    public function show(Request $request, $jenis)
    {
        $tahun = $request->query('tahun', date('Y'));

        // Ambil riwayat data khusus jenis ini
        $riwayat = \App\Models\Utilitas::with(['petugas', 'airBersih', 'airHujan', 'listrikMdp', 'listrikSdp', 'listrikLift', 'listrikAc', 'listrikLampu'])
            ->where('jenis_utilitas', $jenis)
            ->where('periode', 'like', "$tahun-%")
            ->latest('periode')
            ->get();

        // Data untuk Grafik
        $labels = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
        $consumptions = array_fill(0, 12, 0);

        foreach ($riwayat as $data) {
            $bulanIndex = (int) substr($data->periode, 5, 2) - 1;
            if ($bulanIndex >= 0 && $bulanIndex < 12) {
                $consumptions[$bulanIndex] = $data->total_konsumsi;
            }
        }

        return view('admin.utilitas.show', compact('jenis', 'tahun', 'riwayat', 'labels', 'consumptions'));
    }
}
