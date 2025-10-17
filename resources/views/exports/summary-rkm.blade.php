<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Summary RKM TW {{ $tw }}</title>
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
<h3>LAPORAN REALISASI RKM</h3>
<h4>TRIWULAN {{ $tw }}</h4>

<table>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode PP</th>
            <th>Nama PP</th>
            <th>Bidang</th>
            <th>RKM</th>
            <th>Realisasi RKM</th>
            <th>% RKM</th>
        </tr>
    </thead>
    <tbody>
        @foreach($data as $r)
        <tr>
            <td class="text-center">{{ $r['no'] }}</td>
            <td>{{ $r['kode_pp'] }}</td>
            <td>{{ $r['nama_pp'] }}</td>
            <td>{{ $r['bidang'] }}</td>
            <td class="text-end">{{ number_format($r['rkm_operasi'],0,',','.') }}</td>
            <td class="text-end">{{ number_format($r['real_rkm'],0,',','.') }}</td>
            <td class="text-center">{{ $r['persen_rkm'] }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
@include('exports.components.ttd')

</body>

</html>
