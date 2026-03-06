<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Lembar Hasil Ujian</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        .container {
            width: 100%;
        }

        h2 {
            margin-bottom: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 6px 4px;
            vertical-align: top;
        }

        .meta-table td:first-child {
            width: 150px;
            font-weight: bold;
        }

        .soal-box {
            border: 1px solid #ddd;
            padding: 10px;
            margin-bottom: 12px;
            border-radius: 6px;
        }

        .tidak-dikerjakan {
            color: red;
            font-weight: bold;
        }

        .jawaban-benar {
            color: green;
            font-weight: bold;
            border: 1px solid green;
            padding: 2px 4px;
            border-radius: 4px;
        }

        .jawaban-dipilih {
            font-weight: bold;
            border: 1px solid #2563eb;
            padding: 2px 4px;
            border-radius: 4px;
        }

        ul {
            list-style: none;
            padding-left: 10px;
            margin-left: 0;
        }

        li {
            margin-bottom: 4px;
        }
    </style>
</head>

<body>

    <div class="container">

        <h2>Lembar Hasil Ujian</h2>

        <table class="meta-table">
            <tr>
                <td>Nama</td>
                <td>: {{ $event->peserta->name ?? '-' }}</td>
            </tr>
            <tr>
                <td>Kantor</td>
                <td>: {{ $event->kantor->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td>Tanggal Ujian</td>
                <td>: {{ \Carbon\Carbon::parse($event->jadwal_mulai)->format('d M Y') }}</td>
            </tr>
            <tr>
                <td>Waktu Ujian</td>
                <td>:
                    {{ \Carbon\Carbon::parse($event->jadwal_mulai)->format('H:i') }}
                    -
                    {{ \Carbon\Carbon::parse($event->jadwal_berakhir)->format('H:i') }}
                </td>
            </tr>
            <tr>
                <td>Skor</td>
                <td>: {{ $event->skor_akhir }}</td>
            </tr>
            <tr>
                <td>Hasil</td>
                <td>: {{ $event->status_hasil }}</td>
            </tr>
        </table>

        <hr>

        @foreach($data as $index => $item)
        <div class="soal-box">

            @if($item->dikerjakan == 0)
            <div class="tidak-dikerjakan">
                Tidak dikerjakan
            </div>
            @endif

            <strong>
                {{ $index + 1 }}. {{ $item->soal }}
            </strong>

            <ul>
                @foreach($item->opsi_jawaban as $opsIndex => $ops)
                @php
                $label = chr(65 + $opsIndex);
                $class = '';

                if ($item->jawaban_id == $ops->idjawaban) {
                $class = 'jawaban-dipilih';
                }

                if ($ops->skor > 0) {
                $class .= ' jawaban-benar';
                }
                @endphp

                <li>
                    {{ $label }}.
                    <span class="{{ $class }}">
                        {{ $ops->jawaban_opsi }}

                        @if($item->jawaban_id == $ops->idjawaban && $ops->skor > 0)
                        (✔)
                        @elseif($item->jawaban_id == $ops->idjawaban && $ops->skor == 0)
                        (✘)
                        @endif
                    </span>
                </li>
                @endforeach
            </ul>
        </div>
        @endforeach

    </div>

</body>

</html>