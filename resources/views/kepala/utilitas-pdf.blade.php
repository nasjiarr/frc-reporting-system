<html>

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>Laporan Utilitas Terpadu FRC - {{ $periode }}</title>
    <style>
        body {
            font-family: sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2>LAPORAN UTILITAS TERPADU FRC</h2>
        <p>Periode: {{ $periode }}</p>
    </div>
    <table>
        <thead>
            <tr>
                <th>Jenis Utilitas</th>
                <th>Petugas</th>
                <th>Konsumsi</th>
                <th>Tanggal Catat</th>
            </tr>
        </thead>
        <tbody>
            @forelse($details as $item)
            <tr>
                <td>{{ $item->jenis_utilitas }}</td>
                <td>{{ $item->petugas->nama_lengkap ?? '-' }}</td>
                <td style="text-align: right; font-weight: bold;">
                    {{ number_format($item->total_konsumsi, 2, ',', '.') }} {{ str_contains($item->jenis_utilitas, 'Air') ? 'm³' : 'kWh' }}
                </td>
                <td>
                    @if($item->detail && $item->detail->tgl_awal)
                        {{ \Carbon\Carbon::parse($item->detail->tgl_awal)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($item->detail->tgl_akhir)->format('d/m/Y') }}
                    @else
                        {{ $item->periode }}
                    @endif
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" style="text-align: center; color: #777; font-style: italic; padding: 20px;">
                    Belum ada data pencatatan utilitas untuk periode ini.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</body>

</html>