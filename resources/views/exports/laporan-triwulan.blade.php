<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Laporan Triwulan {{ $tw }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
        }

        h3 {
            text-align: center;
            margin-bottom: 5px;
        }

        p {
            text-align: center;
            margin-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 4px;
        }

        th {
            background-color: #f0f0f0;
            font-weight: bold;
        }

        .text-end {
            text-align: right;
        }
    </style>
</head>

<body>
    <h3>Laporan Triwulan {{ $tw }} - {{ $unit ?? 'Semua Unit' }}</h3>
    <p>Dicetak pada: {{ $date }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Unit</th>
                <th>Tipe</th>
                <th>DRK TUP</th>
                <th>Akun</th>
                <th>Nama Akun</th>
                <th>Uraian</th>
                <th>Anggaran</th>
                <th>Realisasi</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($data as $row)
                <tr>
                    <td>{{ $row['no'] }}</td>
                    <td>{{ $row['kode_besar'] }}</td>
                    <td>{{ $row['unit'] }}</td>
                    <td>{{ $row['tipe'] }}</td>
                    <td>{{ $row['drk_tup'] }}</td>
                    <td>{{ $row['akun'] }}</td>
                    <td>{{ $row['nama_akun'] }}</td>
                    <td>{{ $row['uraian'] }}</td>
                    <td class="text-end">{{ $row['anggaran'] }}</td>
                    <td class="text-end">{{ $row['realisasi'] }}</td>
                    <td class="text-end">{{ $row['saldo'] }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="11" style="text-align:center;">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    @include('exports.components.ttd')
</body>

</html>