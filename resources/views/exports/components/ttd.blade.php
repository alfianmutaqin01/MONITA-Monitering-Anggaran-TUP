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
    <table style="width: 100%; font-size: 11px; border-collapse: collapse;">
        <tr>
            {{-- TTD Kiri --}}
            <td style="width: 50%; text-align: center; vertical-align: top; padding: 0 20px;">
                <div style="margin-bottom: 60px;">
                    Mengetahui,<br>
                    <strong>{{ $ttdJabatan1 }}</strong>
                </div>
                <div style="margin-top: 80px;">
                    <strong><u>{{ $ttdNama1 }}</u></strong><br>
                    NIP: {{ $ttdNip1 }}
                </div>
            </td>
            
            {{-- TTD Kanan --}}
            <td style="width: 50%; text-align: center; vertical-align: top; padding: 0 20px;">
                <div style="margin-bottom: 60px;">
                    Purwokerto, {{ $tanggal }}<br>
                    <strong>{{ $ttdJabatan2 }}</strong>
                </div>
                <div style="margin-top: 80px;">
                    <strong><u>{{ $ttdNama2 }}</u></strong><br>
                    NIP: {{ $ttdNip2 }}
                </div>
            </td>
        </tr>
    </table>
</div>