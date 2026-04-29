<!DOCTYPE html>
<html>

<head>
    <title>Laporan Utilitas {{ $jenis }} - {{ $tahun }}</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .header {
            text-align: center;
            margin-bottom: 25px;
            border-bottom: 2px solid #333;
            padding-bottom: 10px;
        }

        .header h2 {
            margin: 0;
            font-size: 18px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #555;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 8px 10px;
            text-align: left;
        }

        th {
            background-color: #f4f4f4;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 11px;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>Laporan Pencatatan Utilitas Gedung FRC</h2>
        <p>Kategori: <strong>{{ $jenis }}</strong> | Tahun Pembukuan: <strong>{{ $tahun }}</strong></p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Periode</th>
                <th width="45%">Petugas Pencatat</th>
                <th width="30%" class="text-right">Total Konsumsi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($riwayat as $index => $data)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td class="font-bold">{{ $data->periode }}</td>
                <td>{{ $data->petugas->nama_lengkap ?? 'Anonim / Dihapus' }}</td>
                <td class="text-right font-bold">
                    {{ number_format($data->total_konsumsi, 2, ',', '.') }}
                    {{ str_contains($jenis, 'Air') ? 'm³' : 'kWh' }}
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="text-center" style="padding: 20px;">Tidak ada data utilitas untuk tahun {{ $tahun }}.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div style="margin-top: 30px; font-size: 10px; color: #777;">
        <p><i>* Dokumen ini digenerate secara otomatis oleh Sistem Informasi Pelaporan FRC pada {{ date('d-m-Y H:i') }}.</i></p>
    </div>
</body>

</html>