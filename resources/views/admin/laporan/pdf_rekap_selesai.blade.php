<!DOCTYPE html>
<html>

<head>
    <title>Rekap Laporan Selesai</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
            color: #333;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #999;
            padding: 6px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background-color: #f8fafc;
            font-weight: bold;
            text-transform: uppercase;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="header">
        <h2 style="margin:0;">REKAPITULASI LAPORAN KERUSAKAN SELESAI</h2>
        <p style="margin:5px 0;">Gedung Field Research Center (FRC) | Periode: {{ $periode }}</p>
    </div>

    <table>
        <thead>
            <tr>
                <th width="3%">No</th>
                <th width="10%">Tanggal</th>
                <th width="12%">Pelapor</th>
                <th width="20%">Kerusakan & Lokasi</th>
                <th width="15%">Teknisi</th>
                <th width="25%">Tindakan Perbaikan</th>
                <th width="15%">Material</th>
            </tr>
        </thead>
        <tbody>
            @foreach($laporans as $index => $laporan)
            <tr>
                <td class="text-center">{{ $index + 1 }}</td>
                <td>{{ $laporan->created_at->format('d/m/Y') }}</td>
                <td>{{ $laporan->pelapor->nama_lengkap ?? '-' }}</td>
                <td>
                    <strong>{{ $laporan->judul }}</strong><br>
                    <small>{{ $laporan->lokasi }}</small>
                </td>
                <td>{{ $laporan->penugasan->teknisi->nama_lengkap ?? '-' }}</td>
                <td>{{ $laporan->penugasan->hasilPerbaikan->tindakan ?? '-' }}</td>
                <td>{{ $laporan->penugasan->hasilPerbaikan->material ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right; font-style: italic;">
        Dicetak pada: {{ date('d F Y H:i') }}
    </div>
</body>

</html>