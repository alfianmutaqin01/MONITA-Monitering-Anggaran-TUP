<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Detail Unit {{ $kode }} - TW {{ $currentTw }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            margin: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        th,
        td {
            border: 1px solid #000;
            padding: 5px;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #d7d7d7;
            text-align: center;
        }

        h3,
        h4 {
            text-align: center;
            margin: 0;
        }

        .text-end {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .summary-table {
            margin-top: 25px;
            width: 60%;
            border-collapse: collapse;
        }

        .summary-table th,
        .summary-table td {
            border: 1px solid #000;
            padding: 5px;
        }

        .summary-table th {
            background: #f2f2f2;
        }
    </style>
</head>

<body>
    <h3>DETAIL ANGGARAN UNIT {{ strtoupper($kode) }}</h3>
    <h4>Triwulan {{ $currentTw }}</h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tipe</th>
                <th>DRK/TUP</th>
                <th>Akun</th>
                <th>Nama Akun</th>
                <th>Uraian</th>
                <th>Anggaran</th>
                <th>Realisasi</th>
                <th>Saldo</th>
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

    <h4 style="margin-top:25px;">Rekap Saldo per Jenis Anggaran</h4>
    <table class="summary-table">
        <thead>
            <tr>
                <th>Jenis Anggaran</th>
                <th class="text-end">Jumlah Saldo (Rp)</th>
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

    <p style="margin-top:20px; font-size:10px; text-align:right;">
        Dicetak pada: {{ now()->format('d F Y H:i') }}
    </p>

</body>

</html>