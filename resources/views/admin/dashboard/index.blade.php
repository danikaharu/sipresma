@extends('layouts.admin.index')

@section('title', 'Dashboard')


@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card mb-3">
            <div class="card-header">
                <h5>Grafik Jumlah Prestasi per Tahun</h5>
            </div>
            <canvas id="activityChart" width="400" height="200"></canvas>
        </div>


    </div>
@endsection

@push('script')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        fetch('/admin/activities/chart-data')
            .then(response => response.json())
            .then(data => {
                const labels = data.map(item => item.year);
                const values = data.map(item => item.total);

                const ctx = document.getElementById('activityChart').getContext('2d');
                new Chart(ctx, {
                    type: 'bar', // bisa diganti 'line' jika ingin
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jumlah Prestasi',
                            data: values,
                            backgroundColor: 'rgba(153, 102, 255, 0.7)',
                            borderColor: 'rgba(153, 102, 255, 1)',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                ticks: {
                                    precision: 0
                                }
                            }
                        }
                    }
                });
            });
    </script>
@endpush
