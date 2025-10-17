<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Summary Pengembangan TW {{ $tw }}</title>
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
    </style>
</head>

<body>
    <h3>LAPORAN REALISASI PENGEMBANGAN (BANGUNAN)</h3>
    <h4>Triwulan {{ $tw }}</h4>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Kode PP</th>
                <th>Nama PP</th>
                <th>Bidang</th>
                <th class="text-end">RKA Bangunan</th>
                <th class="text-end">Realisasi Bangunan</th>
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

    {{-- Sertakan blok TTD jika Anda sudah menambahkan partial seperti saran sebelumnya --}}
    @if (view()->exists('exports.components.ttd'))
        @include('exports.components.ttd')
    @endif
</body>

</html>