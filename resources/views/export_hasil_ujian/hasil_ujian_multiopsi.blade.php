<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @page {
            margin: 12mm 10mm;
        }

        body {
            font-family: DejaVu Sans, Arial, sans-serif;
            font-size: 10px;
            color: #1f2937;
        }

        .title {
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 4px;
        }

        .subtitle {
            font-size: 11px;
            text-align: center;
            margin-bottom: 12px;
            color: #374151;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        thead th {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: bold;
            text-align: center;
            padding: 6px 5px;
            border: 1px solid #9ca3af;
            vertical-align: middle;
        }

        tbody td {
            padding: 6px 5px;
            border: 1px solid #d1d5db;
            vertical-align: top;
        }

        tbody tr:nth-child(even) {
            background-color: #f9fafb;
        }

        .center {
            text-align: center;
        }

        .footer {
            position: fixed;
            bottom: -18px;
            left: 0;
            right: 0;
            font-size: 9px;
            text-align: right;
            color: #6b7280;
        }
    </style>
</head>

<body>

    <div class="title">{{ $judul }}</div>
    <div class="subtitle">Dicetak pada {{ $now }}</div>

    <table>
        <thead>
            <tr>
                <th>Nama Event</th>
                <th class="center">NPP</th>
                <th>Nama Peserta</th>
                <th class="center">Jadwal Mulai</th>
                <th class="center">Jadwal Berakhir</th>
                <th class="center">Waktu Mulai</th>
                <th class="center">Waktu Berakhir</th>
                <th class="center">Skor Akhir</th>
                <th>Kantor</th>
            </tr>
        </thead>

        <tbody>
            @forelse($data as $row)
            <tr>
                <td>{{ $row['event'] }}</td>
                <td class="center">{{ $row['npp'] }}</td>
                <td>{{ $row['nama_peserta'] }}</td>
                <td class="center">{{ $row['jadwal_mulai'] }}</td>
                <td class="center">{{ $row['jadwal_berakhir'] }}</td>
                <td class="center">{{ $row['waktu_mulai'] }}</td>
                <td class="center">{{ $row['waktu_berakhir'] }}</td>
                <td class="center">{{ $row['skor_akhir'] }}</td>
                <td>{{ $row['kantor'] }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="9" class="center">Tidak ada data.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">{{ $judul }}</div>

</body>

</html>