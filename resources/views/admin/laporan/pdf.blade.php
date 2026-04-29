<!DOCTYPE html>
<html>

<head>
    <title>Laporan Perbaikan FRC - {{ $laporan->judul }}</title>
    <style>
        body {
            font-family: Helvetica, Arial, sans-serif;
            font-size: 13px;
            color: #333;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #111;
            margin-bottom: 20px;
            padding-bottom: 10px;
        }

        .header h1 {
            margin: 0;
            font-size: 20px;
            text-transform: uppercase;
        }

        .header p {
            margin: 5px 0 0 0;
            color: #555;
            font-size: 12px;
        }

        .section-title {
            font-size: 14px;
            font-weight: bold;
            background-color: #f3f4f6;
            padding: 5px 10px;
            margin-top: 20px;
            margin-bottom: 10px;
            border-left: 4px solid #4f46e5;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
        }

        td {
            padding: 8px;
            vertical-align: top;
            border-bottom: 1px solid #ddd;
        }

        .label {
            width: 30%;
            font-weight: bold;
            color: #555;
        }

        .photo-container {
            text-align: center;
            margin-top: 15px;
            padding: 10px;
            border: 1px dashed #999;
            background: #fafafa;
        }

        .photo-container img {
            max-width: 100%;
            max-height: 300px;
            object-fit: contain;
        }

        .footer {
            margin-top: 40px;
            font-size: 11px;
            text-align: center;
            color: #777;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
    </style>
</head>

<body>

    <div class="header">
        <h1>BERITA ACARA HASIL PERBAIKAN FRC</h1>
        <p>Dokumen Laporan Kerusakan Gedung dan Utilitas</p>
    </div>

    <div class="section-title">A. INFORMASI LAPORAN</div>
    <table>
        <tr>
            <td class="label">Judul Kerusakan</td>
            <td>: <strong>{{ $laporan->judul }}</strong></td>
        </tr>
        <tr>
            <td class="label">Lokasi</td>
            <td>: {{ $laporan->lokasi }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal Lapor</td>
            <td>: {{ $laporan->created_at->format('d F Y, H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Nama Pelapor</td>
            <td>: {{ $laporan->pelapor->nama_lengkap ?? 'Anonim' }}</td>
        </tr>
    </table>

    <div class="section-title">B. TINDAKAN PERBAIKAN</div>
    <table>
        <tr>
            <td class="label">Nama Teknisi</td>
            <td>: <strong>{{ $laporan->penugasan->teknisi->nama_lengkap ?? '-' }}</strong></td>
        </tr>
        <tr>
            <td class="label">Waktu Selesai</td>
            <td>: {{ $laporan->penugasan->hasilPerbaikan->selesai_pada->format('d F Y, H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Keterangan Tindakan</td>
            <td>: {{ $laporan->penugasan->hasilPerbaikan->tindakan }}</td>
        </tr>
        <tr>
            <td class="label">Material Digunakan</td>
            <td>: {{ $laporan->penugasan->hasilPerbaikan->material ?? '-' }}</td>
        </tr>
    </table>

    <div class="section-title">C. DOKUMENTASI PERBAIKAN</div>
    <table style="border: none; margin-top: 10px;">
        <tr>
            <td style="width: 50%; border: none; text-align: center;">
                <p style="font-weight: bold; color: #e11d48;">KONDISI SEBELUM</p>
                <div style="border: 1px solid #ddd; padding: 5px; height: 200px;">
                    @if($fotoSebelumBase64)
                    <img src="{{ $fotoSebelumBase64 }}" style="max-width: 100%; max-height: 190px; object-fit: contain;">
                    @else
                    <p style="font-size: 10px; color: #999; margin-top: 80px;">Tidak ada foto sebelum</p>
                    @endif
                </div>
            </td>
            <td style="width: 50%; border: none; text-align: center;">
                <p style="font-weight: bold; color: #10b981;">KONDISI SESUDAH</p>
                <div style="border: 1px solid #ddd; padding: 5px; height: 200px;">
                    @if($fotoSesudahBase64)
                    <img src="{{ $fotoSesudahBase64 }}" style="max-width: 100%; max-height: 190px; object-fit: contain;">
                    @else
                    <p style="font-size: 10px; color: #999; margin-top: 80px;">Tidak ada foto sesudah</p>
                    @endif
                </div>
            </td>
        </tr>
    </table>

    <div class="footer">
        <p>Dicetak melalui Sistem Pelaporan FRC pada {{ date('d F Y H:i') }}</p>
    </div>

</body>

</html>