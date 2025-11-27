// views/exports/summary-rka.blade.php
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Realisasi RKA TW {{ $tw }}</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        .header-kop { text-align: center; margin-bottom: 15px; }
        .header-kop h1 { font-size: 16px; margin: 0; padding: 0; }
        .header-kop h2 { font-size: 14px; margin: 0; padding: 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #000; padding: 4px; vertical-align: top; }
        th { background: #d7d7d7; text-align: center; font-weight: bold; }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .info-cetak { font-size: 9px; margin-top: 10px; color: #555; text-align: right; }
    </style>
</head>

<body>
    <div class="header-kop">
        <h1>SISTEM MONITORING ANGGARAN (MONITA)</h1>
        <h2>TELKOM UNIVERSITY PURWOKERTO</h2>
        <hr style="border: 1px solid #000;">
    </div>
    
    <h3 style="text-align: center; margin-top: 0; margin-bottom: 5px; font-size: 14px;">
        LAPORAN REALISASI RKA
    </h3>
    <h4 style="text-align: center; margin-bottom: 15px; font-size: 12px;">
        TRIWULAN {{ $tw }}
    </h4>

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
            </tr>
            @endforeach
        </tbody>
    </table>
    @include('exports.components.ttd')
    <div class="info-cetak">
        <br>
        Dokumen ini dicetak oleh Sistem MONITA pada: {{ $date }}
    </div>
</body>
</html>