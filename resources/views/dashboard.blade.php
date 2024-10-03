@extends('layout')

@section('content')
<div class="container">
    <h2>Dashboard Penggunaan Air</h2>
    <canvas id="usageChart"></canvas>

    <script>
        // Pastikan Chart.js sudah dimuat sebelum menjalankan kode ini
        var ctx = document.getElementById('usageChart').getContext('2d');
        var usageChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Penggunaan Air (m³)',
                    data: @json($data),
                    backgroundColor: 'rgba(40, 167, 69, 0.2)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
</div>
@endsection
