@php
    $tanggal = \Carbon\Carbon::now()->translatedFormat('d F Y');
@endphp

<table style="width:100%; margin-top:40px; font-size:11px;" class="border-0">
    <tr>
        <td style="width:50%; text-align:center; vertical-align:top;">
            Mengetahui<br>
            Wakil Direktur II TUP<br><br><br>
            <img src="{{ public_path('assets/ttd/ttd_tata.png') }}" alt="Tanda tangan Tata"
                style="width:120px; height:auto;"><br>
            <strong><u>Tata Sambada, MBA</u></strong><br>
            NIP : 17740081
        </td>
        <td style="width:50%; text-align:center; vertical-align:top;">
            Purwokerto, {{ $tanggal }}<br>
            Pembuat Rincian<br>
            Kepala Bagian<br><br><br>
            <img src="{{ public_path('assets/ttd/ttd_azis.png') }}" alt="Tanda tangan Azis"
                style="width:120px; height:auto;"><br>
            <strong><u>Abdul Azis, M.Ak</u></strong><br>
            NIP : 13920023
        </td>
    </tr>
</table>