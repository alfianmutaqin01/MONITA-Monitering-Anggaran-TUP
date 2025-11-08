<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Triwulan {{ $tw }}</title>
    <style>
        @page {
            margin: 2cm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 10px; 
        }
        .header-kop {
            text-align: center;
            margin-bottom: 20px;
        }
        .header-kop h1 {
            font-size: 16px;
            margin: 0;
            padding: 0;
        }
        .header-kop h2 {
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        .info-cetak {
            font-size: 9px;
            margin-top: 10px;
            color: #555;
            text-align: right;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        th, td {
            border: 1px solid #000;
            padding: 4px;
            vertical-align: top;
        }
        th {
            background-color: #f0f0f0;
            font-weight: bold;
            text-align: center;
        }
        .text-end { text-align: right; }
        .text-center { text-align: center; }
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
        LAPORAN RAW DATA LENGKAP
    </h3>
    <h4 style="text-align: center; margin-bottom: 15px; font-size: 12px;">
        Triwulan {{ $tw }} - {{ $unit ?? 'Semua Unit' }}
    </h4>

    <table>
        <thead>
            <tr>
                <th style="width: 3%;">No</th>
                <th style="width: 5%;">Kode</th>
                <th style="width: 8%;">Unit</th>
                <th style="width: 6%;">Tipe</th>
                <th style="width: 8%;">DRK TUP</th>
                <th style="width: 5%;">Akun</th>
                <th style="width: 15%;">Nama Akun</th>
                <th style="width: 25%;">Uraian</th>
                <th style="width: 7%;">Anggaran</th>
                <th style="width: 7%;">Realisasi</th>
                <th style="width: 7%;">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalAnggaran = 0;
                $totalRealisasi = 0;
                $totalSaldo = 0;
            @endphp
            @forelse ($data as $row)
                <tr>
                    <td class="text-center">{{ $row['no'] }}</td>
                    <td>{{ $row['kode_besar'] }}</td>
                    <td>{{ $row['unit'] }}</td>
                    <td>{{ $row['tipe'] }}</td>
                    <td>{{ $row['drk_tup'] }}</td>
                    <td>{{ $row['akun'] }}</td>
                    <td>{{ $row['nama_akun'] }}</td>
                    <td>{{ $row['uraian'] }}</td>
                    <td class="text-end">{{ number_format($row['anggaran'], 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($row['realisasi'], 0, ',', '.') }}</td>
                    <td class="text-end">{{ number_format($row['saldo'], 0, ',', '.') }}</td>
                </tr>
                @php
                    $totalAnggaran += $row['anggaran'];
                    $totalRealisasi += $row['realisasi'];
                    $totalSaldo += $row['saldo'];
                @endphp
            @empty
                <tr>
                    <td colspan="11" class="text-center">Tidak ada data</td>
                </tr>
            @endforelse
        </tbody>
        <tfoot>
            <tr style="font-weight: bold; background-color: #e0e0e0;">
                <td colspan="8" style="text-align: center;">TOTAL KESELURUHAN</td>
                <td class="text-end">{{ number_format($totalAnggaran, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($totalRealisasi, 0, ',', '.') }}</td>
                <td class="text-end">{{ number_format($totalSaldo, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
    </table>
    
    @include('exports.components.ttd')

    <div class="info-cetak">
        Dokumen ini dicetak oleh Sistem MONITA pada: {{ $date }}
    </div>
</body>
</html>