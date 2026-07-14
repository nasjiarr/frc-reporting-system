<!DOCTYPE html>
<html>

<head>
    <title>Rekap Laporan Kerusakan</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 10px;
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
            vertical-align: top;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-transform: uppercase;
            font-size: 9px;
        }

        .status-badge {
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 9px;
        }

        .foto-cell {
            text-align: center;
            vertical-align: middle;
        }

        .foto-cell img {
            max-width: 120px;
            max-height: 90px;
            object-fit: contain;
            border: 1px solid #ddd;
            border-radius: 3px;
        }

        .no-foto {
            color: #999;
            font-size: 8px;
            font-style: italic;
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
                <th width="3%">No</th>
                <th width="8%">Tanggal</th>
                <th width="10%">Pelapor</th>
                <th width="16%">Kerusakan & Lokasi</th>
                <th width="10%">Teknisi</th>
                <th width="7%">Status</th>
                <th width="23%">Foto Sebelum</th>
                <th width="23%">Foto Sesudah</th>
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
                <td class="foto-cell">
                    @if($laporan->foto_sebelum_base64)
                        <img src="{{ $laporan->foto_sebelum_base64 }}" alt="Foto Sebelum">
                    @else
                        <span class="no-foto">Tidak ada foto</span>
                    @endif
                </td>
                <td class="foto-cell">
                    @if($laporan->foto_sesudah_base64)
                        <img src="{{ $laporan->foto_sesudah_base64 }}" alt="Foto Sesudah">
                    @else
                        <span class="no-foto">Tidak ada foto</span>
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 20px; text-align: right;">
        <p>Dicetak pada: {{ date('d F Y H:i') }}</p>
    </div>
</body>

</html>