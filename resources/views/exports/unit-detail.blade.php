<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Unit {{ $kode }} - TW {{ $currentTw }}</title>
    <style>
        @page { margin: 2cm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }

        .header-kop { text-align: center; margin-bottom: 15px; }
        .header-kop h1 { font-size: 16px; margin: 0; padding: 0; }
        .header-kop h2 { font-size: 14px; margin: 0; padding: 0; }

        table { width: 100%; border-collapse: collapse; margin-top: 5px; }
        th, td { border: 1px solid #000; padding: 4px; vertical-align: top; }
        th { background: #d7d7d7; text-align: center; font-weight: bold; }

        .summary-table { margin-top: 25px; width: 60%; border-collapse: collapse; margin-left: 0; }
        .summary-table th, .summary-table td { border: 1px solid #000; padding: 5px; }
        .summary-table th { background: #f2f2f2; text-align: left; }
        
        .text-end { text-align: right; }
        .text-center { text-align: center; }
        .info-cetak { font-size: 9px; margin-top: 10px; color: #555; text-align: right; }
    </style>
</head>

<body>
    {{-- KOP DOKUMEN --}}
    <div class="header-kop">
        <h1>SISTEM MONITORING ANGGARAN (MONITA)</h1>
        <h2>TELKOM UNIVERSITY PURWOKERTO</h2>
        <hr style="border: 1px solid #000;">
    </div>
    
    <h3 style="text-align: center; margin-top: 0; margin-bottom: 5px; font-size: 14px;">
        DETAIL ANGGARAN UNIT {{ strtoupper($kode) }}
    </h3>
    <h4 style="text-align: center; margin-bottom: 15px; font-size: 12px;">
        Triwulan {{ $currentTw }}
    </h4>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 6%;">Tipe</th>
                <th style="width: 10%;">DRK/TUP</th>
                <th style="width: 7%;">Akun</th>
                <th style="width: 18%;">Nama Akun</th>
                <th style="width: 25%;">Uraian</th>
                <th style="width: 8%;">Anggaran</th>
                <th style="width: 8%;">Realisasi</th>
                <th style="width: 8%;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @forelse($data as $i => $r)
                <tr>
                    <td class="text-center">{{ $i + 1 }}</td>
                    <td>{{ $r['tipe'] }}</td>
                    <td>{{ $r['drk_tup'] }}</td>
                    <td>{{ $r['akun'] }}</td>
                    <td>{{ $r['nama_akun'] }}</td>
                    <td>{{ $r['uraian'] }}</td>
                    <td class="text-end">{{ number_format($r['anggaran'], 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($r['realisasi'], 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($r['saldo'], 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data anggaran ditemukan.</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight:bold; background:#f0f0f0;">
                <td colspan="6" class="text-center">TOTAL KESELURUHAN</td>
                <td class="text-end">{{ number_format($totalAnggaran ?? 0, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($totalRealisasi ?? 0, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($totalSaldo ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>

    {{-- 🔹 Rekap per jenis anggaran --}}
    @php
        $sumByType = ['OPERASIONAL' => 0, 'REMUN' => 0, 'BANG' => 0, 'NTF' => 0];
        foreach ($data as $r) {
            $t = strtoupper($r['tipe']);
            $s = (float) $r['saldo'];
            if (str_contains($t, 'OPER'))
                $sumByType['OPERASIONAL'] += $s;
            elseif (str_contains($t, 'REMUN'))
                $sumByType['REMUN'] += $s;
            elseif (str_contains($t, 'BANG'))
                $sumByType['BANG'] += $s;
            elseif (str_contains($t, 'NTF'))
                $sumByType['NTF'] += $s;
        }
    @endphp

    <h4 style="margin-top:25px; text-align: left;">Rekap Saldo per Jenis Anggaran</h4>
    <table class="summary-table">
        <thead>
            <tr>
                <th style="width: 50%;">Jenis Anggaran</th>
                <th style="width: 50%;" class="text-end">Jumlah Saldo (Rp)</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Operasional</td>
                <td class="text-end">{{ number_format($sumByType['OPERASIONAL'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Remun</td>
                <td class="text-end">{{ number_format($sumByType['REMUN'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Pembangunan (Bang)</td>
                <td class="text-end">{{ number_format($sumByType['BANG'], 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>NTF</td>
                <td class="text-end">{{ number_format($sumByType['NTF'], 0, ',', '.') }}</td>
            </tr>
            <tr style="font-weight:bold; background:#f0f0f0;">
                <td>Total Semua Jenis</td>
                <td class="text-end">{{ number_format(array_sum($sumByType), 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>
    
    @include('exports.components.ttd')

    <div class="info-cetak">
        Dokumen ini dicetak oleh Sistem MONITA pada: {{ $date }}
    </div>
</body>

</html>