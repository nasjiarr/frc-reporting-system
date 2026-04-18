<html>

<head>
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
            @foreach($details as $item)
            <tr>
                <td>{{ $item->jenis_utilitas }}</td>
                <td>{{ $item->petugas->nama_lengkap }}</td>
                <td>{{ $item->detail->konsumsi ?? '-' }}</td>
                <td>{{ $item->created_at->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>