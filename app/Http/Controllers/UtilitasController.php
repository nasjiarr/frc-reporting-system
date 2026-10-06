<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Models\Utilitas;
use App\Models\AirBersih;
use App\Models\AirHujan;
use App\Models\ListrikMdp;
use App\Models\ListrikSdp;
use App\Models\ListrikLift;
use App\Models\ListrikAc;
use App\Models\ListrikLampu;
use Barryvdh\DomPDF\Facade\Pdf;

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
        $jenis = $request->jenis_utilitas;

        // 1. Validasi Input Dasar & Spesifik
        $rules = [
            'jenis_utilitas' => ['required', Rule::in(Utilitas::JENIS_UTILITAS)],
            'periode'        => [
                'required',
                'date_format:Y-m',
                Rule::unique('utilitas', 'periode')->where(fn ($query) => $query->where('jenis_utilitas', $jenis)),
            ],
            'tgl_awal'       => ['required', 'date'],
            'tgl_akhir'      => ['required', 'date', 'after_or_equal:tgl_awal'],
        ];

        if (in_array($jenis, ['AirBersih', 'AirHujan', 'MDP'])) {
            $rules['stand_awal']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir'] = ['required', 'numeric', 'gte:stand_awal'];
        } elseif (in_array($jenis, ['SDP', 'Lift'])) {
            $rules['stand_awal_1']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_1'] = ['required', 'numeric', 'gte:stand_awal_1'];
            $rules['stand_awal_2']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_2'] = ['required', 'numeric', 'gte:stand_awal_2'];
        } elseif (in_array($jenis, ['AC', 'Lampu'])) {
            $rules['stand_awal_l1']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_l1'] = ['required', 'numeric', 'gte:stand_awal_l1'];
            $rules['stand_awal_l2']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_l2'] = ['required', 'numeric', 'gte:stand_awal_l2'];
            $rules['stand_awal_l3']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_l3'] = ['required', 'numeric', 'gte:stand_awal_l3'];
        }

        $customMessages = [
            'periode.unique' => 'Data utilitas untuk jenis dan periode ini sudah ada.',
            'tgl_akhir.after_or_equal' => 'Tanggal akhir harus sama dengan atau setelah tanggal awal.',
            'stand_akhir.gte' => 'Stand meter akhir tidak boleh lebih kecil dari stand meter awal.',
            'stand_akhir_1.gte' => 'Stand meter akhir (1) tidak boleh lebih kecil dari stand awal.',
            'stand_akhir_2.gte' => 'Stand meter akhir (2) tidak boleh lebih kecil dari stand awal.',
            'stand_akhir_l1.gte' => 'Stand akhir Lantai 1 tidak boleh lebih kecil dari stand awal.',
            'stand_akhir_l2.gte' => 'Stand akhir Lantai 2 tidak boleh lebih kecil dari stand awal.',
            'stand_akhir_l3.gte' => 'Stand akhir Lantai 3 tidak boleh lebih kecil dari stand awal.',
        ];

        $request->validate($rules, $customMessages);

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

        if (request()->wantsJson()) {
            return response()->json($utilitas);
        }

        return view('admin.utilitas.edit', compact('utilitas'));
    }

    public function update(Request $request, $id)
    {
        $utilitas = \App\Models\Utilitas::findOrFail($id);
        $jenis = $utilitas->jenis_utilitas;

        $rules = [
            'tgl_awal'  => ['required', 'date'],
            'tgl_akhir' => ['required', 'date', 'after_or_equal:tgl_awal'],
        ];

        if ($request->filled('periode')) {
            $rules['periode'] = [
                'required',
                'date_format:Y-m',
                Rule::unique('utilitas', 'periode')
                    ->where(fn ($query) => $query->where('jenis_utilitas', $jenis))
                    ->ignore($utilitas->id),
            ];
        }

        if (in_array($jenis, ['AirBersih', 'AirHujan', 'MDP'])) {
            $rules['stand_awal']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir'] = ['required', 'numeric', 'gte:stand_awal'];
        } elseif ($jenis === 'SDP') {
            $rules['stand_awal_sdp1']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_sdp1'] = ['required', 'numeric', 'gte:stand_awal_sdp1'];
            $rules['stand_awal_sdp2']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_sdp2'] = ['required', 'numeric', 'gte:stand_awal_sdp2'];
        } elseif ($jenis === 'Lift') {
            $rules['stand_awal_g']   = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_g']  = ['required', 'numeric', 'gte:stand_awal_g'];
            $rules['stand_awal_g2']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_g2'] = ['required', 'numeric', 'gte:stand_awal_g2'];
        } elseif (in_array($jenis, ['AC', 'Lampu'])) {
            $rules['stand_awal_l1']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_l1'] = ['required', 'numeric', 'gte:stand_awal_l1'];
            $rules['stand_awal_l2']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_l2'] = ['required', 'numeric', 'gte:stand_awal_l2'];
            $rules['stand_awal_l3']  = ['required', 'numeric', 'min:0'];
            $rules['stand_akhir_l3'] = ['required', 'numeric', 'gte:stand_awal_l3'];
        }

        $customMessages = [
            'periode.unique' => 'Data utilitas untuk jenis dan periode ini sudah ada.',
            'tgl_akhir.after_or_equal' => 'Tanggal akhir harus sama dengan atau setelah tanggal awal.',
            'stand_akhir.gte' => 'Stand meter akhir tidak boleh lebih kecil dari stand meter awal.',
            'stand_akhir_sdp1.gte' => 'Stand akhir SDP 1 tidak boleh lebih kecil dari stand awal.',
            'stand_akhir_sdp2.gte' => 'Stand akhir SDP 2 tidak boleh lebih kecil dari stand awal.',
            'stand_akhir_g.gte' => 'Stand akhir Lift G tidak boleh lebih kecil dari stand awal.',
            'stand_akhir_g2.gte' => 'Stand akhir Lift G2 tidak boleh lebih kecil dari stand awal.',
            'stand_akhir_l1.gte' => 'Stand akhir Lantai 1 tidak boleh lebih kecil dari stand awal.',
            'stand_akhir_l2.gte' => 'Stand akhir Lantai 2 tidak boleh lebih kecil dari stand awal.',
            'stand_akhir_l3.gte' => 'Stand akhir Lantai 3 tidak boleh lebih kecil dari stand awal.',
        ];

        $request->validate($rules, $customMessages);

        return DB::transaction(function () use ($request, $utilitas) {
            // 1. Update Tabel Induk
            if ($request->has('periode')) {
                $utilitas->update([
                    'periode' => $request->periode,
                ]);
            }

            // 2. Update Tabel Turunan berdasarkan jenis
            switch ($utilitas->jenis_utilitas) {
                case 'AirBersih':
                case 'AirHujan':
                case 'MDP':
                    $relation = match ($utilitas->jenis_utilitas) {
                        'AirBersih' => 'airBersih',
                        'AirHujan'  => 'airHujan',
                        'MDP'       => 'listrikMdp',
                    };
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

            return redirect()->route('admin.utilitas.show', $utilitas->jenis_utilitas)->with('success', 'Data utilitas berhasil diperbarui.');
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
        abort_unless(in_array($jenis, Utilitas::JENIS_UTILITAS), 404);

        return redirect()->route('admin.utilitas.show', [
            'jenis' => $jenis,
            'tahun' => $request->query('tahun', date('Y'))
        ]);
    }

    public function index()
    {
        $kategoriConfig = [
            'AirBersih' => ['nama' => 'Air Bersih', 'icon' => 'droplet', 'warna' => 'blue', 'unit' => 'm³'],
            'AirHujan'  => ['nama' => 'Air Hujan', 'icon' => 'cloud-rain', 'warna' => 'cyan', 'unit' => 'm³'],
            'MDP'       => ['nama' => 'Listrik MDP (Panel Utama)', 'icon' => 'bolt', 'warna' => 'amber', 'unit' => 'kWh'],
            'SDP'       => ['nama' => 'Listrik SDP (Panel Distribusi)', 'icon' => 'zap', 'warna' => 'yellow', 'unit' => 'kWh'],
            'Lift'      => ['nama' => 'Listrik Lift', 'icon' => 'arrow-up-down', 'warna' => 'orange', 'unit' => 'kWh'],
            'AC'        => ['nama' => 'Listrik AC (3 Lantai)', 'icon' => 'wind', 'warna' => 'indigo', 'unit' => 'kWh'],
            'Lampu'     => ['nama' => 'Listrik Lampu (3 Lantai)', 'icon' => 'lightbulb', 'warna' => 'emerald', 'unit' => 'kWh'],
        ];

        $allUtilitas = Utilitas::with(['airBersih', 'airHujan', 'listrikMdp', 'listrikSdp', 'listrikLift', 'listrikAc', 'listrikLampu'])
            ->orderBy('periode', 'desc')
            ->get()
            ->groupBy('jenis_utilitas');

        $jenisUtilitas = [];
        $totalListrikBulanIni = 0;
        $totalAirBulanIni = 0;
        $periodeTerbaru = null;

        foreach ($kategoriConfig as $slug => $cfg) {
            $records = $allUtilitas->get($slug, collect());
            $latest = $records->first();
            $previous = $records->count() > 1 ? $records->get(1) : null;

            $latestKonsumsi = $latest ? $latest->total_konsumsi : null;
            $prevKonsumsi = $previous ? $previous->total_konsumsi : null;

            $tren = null;
            $trenPersen = null;
            if ($latestKonsumsi !== null && $prevKonsumsi !== null && $prevKonsumsi > 0) {
                $diff = $latestKonsumsi - $prevKonsumsi;
                $trenPersen = round(($diff / $prevKonsumsi) * 100, 1);
                $tren = $trenPersen > 0 ? 'up' : ($trenPersen < 0 ? 'down' : 'same');
            }

            if ($latest) {
                if (!$periodeTerbaru || $latest->periode > $periodeTerbaru) {
                    $periodeTerbaru = $latest->periode;
                }
                if (in_array($slug, ['AirBersih', 'AirHujan'])) {
                    $totalAirBulanIni += $latestKonsumsi;
                } elseif ($slug === 'MDP') {
                    $totalListrikBulanIni += $latestKonsumsi;
                }
            }

            $jenisUtilitas[] = [
                'nama'           => $cfg['nama'],
                'slug'           => $slug,
                'icon'           => $cfg['icon'],
                'warna'          => $cfg['warna'],
                'unit'           => $cfg['unit'],
                'latest_periode' => $latest?->periode,
                'latest_konsumsi'=> $latestKonsumsi,
                'tren'           => $tren,
                'tren_persen'    => $trenPersen !== null ? abs($trenPersen) : null,
                'total_records'  => $records->count(),
            ];
        }

        return view('admin.utilitas.index', compact(
            'jenisUtilitas',
            'totalListrikBulanIni',
            'totalAirBulanIni',
            'periodeTerbaru'
        ));
    }

    public function show(Request $request, $jenis)
    {
        abort_unless(in_array($jenis, Utilitas::JENIS_UTILITAS), 404);

        $tahun = $request->query('tahun', date('Y'));

        // Ambil riwayat data khusus jenis ini
        $riwayat = Utilitas::with(['petugas', 'airBersih', 'airHujan', 'listrikMdp', 'listrikSdp', 'listrikLift', 'listrikAc', 'listrikLampu'])
            ->where('jenis_utilitas', $jenis)
            ->where('periode', 'like', "$tahun-%")
            ->orderBy('periode', 'desc')
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

        // Statistik Ringkasan Tahun Berjalan
        $recordedValues = array_filter($consumptions, fn ($v) => $v > 0);
        $totalTahunIni = array_sum($consumptions);
        $rataRataBulanan = count($recordedValues) > 0 ? $totalTahunIni / count($recordedValues) : 0;
        $konsumsiTertinggi = count($recordedValues) > 0 ? max($recordedValues) : 0;

        return view('admin.utilitas.show', compact(
            'jenis',
            'tahun',
            'riwayat',
            'labels',
            'consumptions',
            'totalTahunIni',
            'rataRataBulanan',
            'konsumsiTertinggi'
        ));
    }

    public function exportPdf(Request $request, $jenis)
    {
        abort_unless(in_array($jenis, Utilitas::JENIS_UTILITAS), 404);

        $tahun = $request->query('tahun', date('Y'));

        $relation = match ($jenis) {
            'AirBersih' => 'airBersih',
            'AirHujan'  => 'airHujan',
            'MDP'       => 'listrikMdp',
            'SDP'       => 'listrikSdp',
            'Lift'      => 'listrikLift',
            'AC'        => 'listrikAc',
            'Lampu'     => 'listrikLampu',
            default     => null
        };

        // Tarik data riwayat utilitas untuk diekspor ke PDF (eager loading cegah N+1)
        $riwayat = Utilitas::with(array_filter(['petugas', $relation]))
            ->where('jenis_utilitas', $jenis)
            ->where('periode', 'like', "$tahun-%")
            ->latest('periode')
            ->get();

        $pdf = Pdf::loadView('admin.utilitas.pdf', compact('jenis', 'tahun', 'riwayat'));
        $pdf->setPaper('A4', 'portrait');

        return $pdf->download("Laporan_Utilitas_{$jenis}_{$tahun}.pdf");
    }

    public function utilitasExportPdf(Request $request, $jenis)
    {
        return $this->exportPdf($request, $jenis);
    }
}
