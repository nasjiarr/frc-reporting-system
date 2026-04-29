<!DOCTYPE html>
<html>

<head>
    <title>Rekap Laporan Kerusakan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 11px;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 6px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
        }

        .status-badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9px;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2 style="margin:0;">REKAPITULASI LAPORAN KERUSAKAN GEDUNG FRC</h2>
        <p style="margin:5px 0;">Filter Status: {{ $filters['status'] }} | Periode: {{ $filters['periode'] }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Pelapor</th>
                <th>Kerusakan & Lokasi</th>
                <th>Teknisi</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $laporan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $laporan->created_at->format('d/m/Y') }}</td>
                <td>{{ $laporan->pelapor->nama_lengkap ?? '-' }}</td>
                <td>
                    <strong>{{ $laporan->judul }}</strong><br>
                    <small>{{ $laporan->lokasi }}</small>
                </td>
                <td>{{ $laporan->penugasan->teknisi->nama_lengkap ?? 'Belum Ditugaskan' }}</td>
                <td>{{ $laporan->status }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right;">
        <p>Dicetak pada: {{ date('d F Y H:i') }}</p>
    </div>
</body>

</html>