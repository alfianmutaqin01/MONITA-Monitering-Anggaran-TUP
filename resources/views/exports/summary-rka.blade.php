<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Summary RKA TW {{ $tw }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; margin: 10px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border: 1px solid #000; padding: 5px; }
        th { background: #d7d7d7; text-align: center; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
    </style>
</head>

<body>
<h3>LAPORAN REALISASI RKA</h3>
<h4>TRIWULAN {{ $tw }}</h4>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode PP</th>
            <th>Nama PP</th>
            <th>Bidang</th>
            <th>Anggaran TW {{ $tw }}</th>
            <th>Realisasi TW {{ $tw }}</th>
            <th>Saldo TW {{ $tw }}</th>
            <th>% Serapan All</th>
            <th>RKA Operasi</th>
            <th>Real Operasi</th>
            <th>Saldo Operasi</th>
            <th>% Serapan Operasi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $r)
        <tr>
            <td class="text-center">{{ $r['no'] }}</td>
            <td>{{ $r['kode_pp'] }}</td>
            <td>{{ $r['nama_pp'] }}</td>
            <td>{{ $r['bidang'] }}</td>
            <td class="text-end">{{ number_format($r['anggaran_tw'],0,',','.') }}</td>
            <td class="text-end">{{ number_format($r['realisasi_tw'],0,',','.') }}</td>
            <td class="text-end">{{ number_format($r['saldo_tw'],0,',','.') }}</td>
            <td class="text-center">{{ $r['serapan_all'] }}</td>
            <td class="text-end">{{ number_format($r['rka_operasi'],0,',','.') }}</td>
            <td class="text-end">{{ number_format($r['real_operasi'],0,',','.') }}</td>
            <td class="text-end">{{ number_format($r['saldo_operasi'],0,',','.') }}</td>
            <td class="text-center">{{ $r['serapan_oper'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@include('exports.components.ttd')

</body>

</html>
