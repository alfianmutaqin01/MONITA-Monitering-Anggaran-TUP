<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Realisasi Pengembangan TW {{ $tw }}</title>
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
        LAPORAN REALISASI BANG    </h3>
    <h4 style="text-align: center; margin-bottom: 15px; font-size: 12px;">
        Triwulan {{ $tw }}
    </h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode PP</th>
                <th>Nama PP</th>
                <th>Bidang</th>
                <th class="text-end">RKA bang</th>
                <th class="text-end">Realisasi bang</th>
                <th>Sisa Bang</th>
                <th>% Serapan Bang</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $r)
                <tr>
                    <td class="text-center">{{ $r['no'] }}</td>
                    <td>{{ $r['kode_pp'] }}</td>
                    <td>{{ $r['nama_pp'] }}</td>
                    <td>{{ $r['bidang'] }}</td>
                    <td class="text-end">{{ number_format($r['rka_bang'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($r['real_bang'] ?? 0, 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($r['sisa_bang'], 0, ',', '.') }}</td>
                    <td class="text-center">{{ $r['serapan_bang'] ?? '-' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="8" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
    </table>
    
    @include('exports.components.ttd')

    <div class="info-cetak">
        <br>
        Dokumen ini dicetak oleh Sistem MONITA pada: {{ $date }}
    </div>
</body>
</html>