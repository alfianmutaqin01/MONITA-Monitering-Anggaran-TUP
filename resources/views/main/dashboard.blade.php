@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
    <div class="row">
        {{-- Kartu Saldo Triwulan --}}
        <div class="col-xl-3 col-md-6">
            <div class="card bg-secondary-dark dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="col-auto">
                        <div class="bg-dark bg-opacity-25 rounded d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px;">
                            <span class="text-white fw-bold fs-3">I</span>
                        </div>
                    </div>
                    <div class="amount-wrapper mt-1">
                        <span class="text-white amount-text">
                            Rp {{ number_format($saldoTW1, 0, ',', '.') }}
                        </span>
                    </div>
                    <p class="mb-0 opacity-75">Sisa Saldo Triwulan 1</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-teal-900 dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="col-auto">
                        <div class="bg-dark bg-opacity-25 rounded d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px;">
                            <span class="text-white fw-bold fs-3">II</span>
                        </div>
                    </div>
                    <div class="amount-wrapper mt-1">
                        <span class="text-white amount-text">
                            Rp {{ number_format($saldoTW2, 0, ',', '.') }}
                        </span>
                    </div>

                    <p class="mb-0 opacity-75">Sisa Saldo Triwulan 2</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-yellow-900 dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="col-auto">
                        <div class="bg-dark bg-opacity-25 rounded d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px;">
                            <span class="text-white fw-bold fs-3">III</span>
                        </div>
                    </div>
                    <div class="amount-wrapper mt-1">
                        <span class="text-white amount-text">
                            Rp {{ number_format($saldoTW3, 0, ',', '.') }}
                        </span>
                    </div>
                    <p class="mb-0 opacity-75">Sisa Saldo Triwulan 3</p>
                </div>
            </div>
        </div>
        <div class="col-xl-3 col-md-6">
            <div class="card bg-primary-dark dashnum-card text-white overflow-hidden">
                <span class="round small"></span>
                <span class="round big"></span>
                <div class="card-body">
                    <div class="col-auto">
                        <div class="bg-dark bg-opacity-25 rounded d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px;">
                            <span class="text-white fw-bold fs-3">IV</span>
                        </div>
                    </div>
                    <div class="amount-wrapper mt-1">
                        <span class="text-white amount-text">
                            Rp {{ number_format($saldoTW4, 0, ',', '.') }}
                        </span>
                    </div>
                    <p class="mb-0 opacity-75">Sisa Saldo Triwulan 4</p>
                </div>

            </div>
        </div>
    </div>
    </div>

    <div class="card m-4">
        <div class="card-header align-items-center">
            <small class="fw-semibold">Keterangan Warna:</small>
            <div class="d-flex flex-wrap gap-3 mt-2 mb-3">
                <span><span class="legend" style="background:#a31d1d"></span> Merah (Sangat Rendah 0–25%)</span>
                <span><span class="legend" style="background:#6c3c0c"></span> Coklat (Rendah 25,01–50%)</span>
                <span><span class="legend" style="background:#d8b100"></span> Kuning (Sedang 50,01–75%)</span>
                <span><span class="legend" style="background:#28a745"></span> Hijau (Tinggi 75,01–100%)</span>
                <span><span class="legend" style="background:#5c77b1"></span> Biru (Over >100%)</span>
            </div>
        </div>
        <div class="card-body">
            {{--Pilih Triwulan --}}
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="fw-bold mb-0">Detail Serapan per Unit TUP</h2>
                <form method="get" id="twForm" class="mb-0">
                    <select name="tw" class="form-select" style="font-size:0,8rem; padding:.6rem 1rem; min-width:180px;">
                        @for ($i = 1; $i <= 4; $i++)
                            <option value="{{ $i }}" {{ $currentTw == $i ? 'selected' : '' }}>Triwulan {{ $i }}</option>
                        @endfor
                    </select>
                </form>
            </div>

            {{-- PERBAIKAN: Bungkus setiap chart dengan div responsive agar scroll horizontal terjadi di dalam card --}}
            <div class="chart-container-wrapper table-responsive">
                <div id="chart-serapan" style="height:320px; width: 1000px; min-width: 100%;"></div>
            </div>

            <div class="chart-container-wrapper table-responsive">
                <div id="chart-rka" style="height:320px; width: 1000px; min-width: 100%;"></div>
            </div>

            <div class="chart-container-wrapper table-responsive">
                <div id="chart-operasional" style="height:320px; width: 1000px; min-width: 100%;"></div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const labels = {!! json_encode($labels) !!};
            const dataSerapan = {!! json_encode($dataSerapan, JSON_NUMERIC_CHECK) !!};
            const dataRka = {!! json_encode($dataRka, JSON_NUMERIC_CHECK) !!};
            const dataOperasional = {!! json_encode($dataOperasional, JSON_NUMERIC_CHECK) !!};

            function colorByValue(v) {
                if (v <= 25) return "#a31d1d";
                if (v <= 50) return "#6c3c0c";
                if (v <= 75) return "#d8b100";
                if (v <= 100) return "#28a745";
                return "#5c77b1";
            }

            const maxVal = arr => Math.ceil(Math.max(...arr, 100) / 10) * 10;

            // PERBAIKAN: Tentukan lebar chart yang lebih lebar dari container normal (misal 1000px)
            // Ini akan memaksa div wrapper yang responsive (table-responsive) untuk scroll.
            const chartWidth = Math.max(1000, labels.length * 40);

            const chartConfigs = [
                { id: "#chart-serapan", title: "Serapan kegiatan Unit(%)", data: dataSerapan },
                { id: "#chart-rka", title: "Serapan RKA Operasi (%)", data: dataRka }, // Mengganti nama chart agar lebih jelas
                { id: "#chart-operasional", title: "Real Operasional Unit (%)", data: dataOperasional },
            ];

            document.dispatchEvent(new Event('monita:loading:start'));

            chartConfigs.forEach(cfg => {
                const element = document.querySelector(cfg.id);

                // Atur lebar div chart di sini, agar bisa discroll
                element.style.width = `${chartWidth}px`;

                const options = {
                    chart: {
                        type: "bar",
                        height: 320,
                        width: chartWidth, // Terapkan lebar yang dihitung ke opsi chart
                        toolbar: { show: false }
                    },
                    series: [{ name: cfg.title, data: cfg.data }],
                    colors: cfg.data.map(colorByValue),
                    plotOptions: { bar: { distributed: true, columnWidth: "60%" } },
                    xaxis: { categories: labels, labels: { rotate: -45, style: { fontSize: "11px" } } },
                    yaxis: {
                        min: 0,
                        max: maxVal(cfg.data),
                        labels: { formatter: v => v + "%" },
                        title: { text: "Persentase" }
                    },
                    dataLabels: { enabled: false },
                    tooltip: { y: { formatter: val => val + " %" } },
                    title: { text: cfg.title, align: "center" },
                    legend: { show: false }
                };
                new ApexCharts(element, options).render();
            });

            setTimeout(() => document.dispatchEvent(new Event('monita:loading:end')), 1000);

            document.querySelector('#twForm select').addEventListener('change', () => {
                document.dispatchEvent(new Event('monita:loading:start'));
                document.getElementById('twForm').submit();
            });
        });
    </script>

    <style>
        .legend {
            display: inline-block;
            width: 18px;
            height: 12px;
            border-radius: 3px;
            margin-right: 4px;
        }

        /* CSS Tambahan untuk memastikan chart container memiliki scroll bar saat lebar */
        .chart-container-wrapper {
            width: 100%;
            overflow-x: auto;
            overflow-y: hidden;
            padding-bottom: 20px;
            /* Ruang untuk scrollbar horizontal */
        }
    </style>
@endsection