@php
    // Data TTD default untuk menghindari error
    $ttdJabatan1 = env('TTD_JABATAN_1', 'Jabatan Penanda Tangan 1');
    $ttdNama1 = env('TTD_NAMA_1', 'Nama Penanda Tangan 1');
    $ttdNip1 = env('TTD_NIP_1', 'NIP 1');

    $ttdJabatan2 = env('TTD_JABATAN_2', 'Jabatan Penanda Tangan 2');
    $ttdNama2 = env('TTD_NAMA_2', 'Nama Penanda Tangan 2');
    $ttdNip2 = env('TTD_NIP_2', 'NIP 2');

    $tanggal = \Carbon\Carbon::now()->translatedFormat('d F Y');
@endphp

<div style="margin-top: 60px; padding-top: 20px; border-top: 1px solid #ddd;">
    {{-- Hapus border-collapse: collapse; karena ini adalah tabel pembatas. --}}
    <table style="width: 100%; font-size: 11px; border: none; border-collapse: collapse;">
        {{-- Hapus tag <h5> yang tidak valid di dalam <tr> --}}

            <tr style="border: none;">
                {{-- TTD Kiri --}}
                <td style="width: 50%; text-align: center; vertical-align: top; padding: 0 20px; border: none;">
                    {{-- PERBAIKAN: Tanggal ditaruh di kolom Kanan (Pembuat) --}}
                    <div style="margin-bottom: 60px;">
                        Mengetahui,<br>
                        {{ $ttdJabatan1 }}
                    </div>
                    <div style="margin-top: 80px;">
                        <u>{{ $ttdNama1 }}</u><br>
                        NIP: {{ $ttdNip1 }}
                    </div>
                </td>

                {{-- TTD Kanan --}}
                <td style="width: 50%; text-align: center; vertical-align: top; padding: 0 20px; border: none;">
                    <div style="margin-bottom: 60px;">
                        Purwokerto, {{ $tanggal }}<br>
                        {{ $ttdJabatan2 }}
                    </div>
                    <div style="margin-top: 80px;">
                        <u>{{ $ttdNama2 }}</u><br>
                        NIP: {{ $ttdNip2 }}
                    </div>
                </td>
            </tr>
    </table>
</div>