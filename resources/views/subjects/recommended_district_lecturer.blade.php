<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bangla Subject Info</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .highlight {
            background-color: yellow;
            font-weight: bold;
            padding: 2px 5px;
            border-radius: 3px;
        }
    </style>
</head>
<body class="container mt-4">

    <h1 class="text-center mb-4">
        বাংলা প্রভাষক সুপারিশ জেলাভিত্তিক নম্বর তালিকা
    </h1>

    <!-- Chart -->
    <div class="card mb-4">
        <div class="card-body">
            <h5 class="text-center">District-wise Lowest vs Highest Marks</h5>
            <canvas id="districtChart" height="120"></canvas>
        </div>
    </div>

    <!-- Table -->
    <table class="table table-bordered table-striped align-middle">
        <thead class="table-dark text-center">
            <tr>
                <th>SL</th>
                <th colspan="3">Lowest Marks</th>
                <th colspan="3">Highest Marks</th>
            </tr>
            <tr class="table-secondary text-center">
                <th></th>
                <th>Institute</th>
                <th>District - Thana</th>
                <th>Marks</th>
                <th>Institute</th>
                <th>District - Thana</th>
                <th>Marks</th>
            </tr>
        </thead>
        <tbody>
            @forelse($listOfData as $index => $row)
                <tr>
                    <td class="text-center">{{ $listOfData->firstItem() + $index }}</td>

                    <td>{{ $row->lowest_institute }}</td>
                    <td>{{ $row->institute_district }} - {{ $row->lowest_thana }}</td>
                    <td class="text-center"><span class="highlight">{{ number_format($row->lowest_marks, 2) }}</span></td>

                    <td>{{ $row->highest_institute }}</td>
                    <td>{{ $row->institute_district }} - {{ $row->highest_thana }}</td>
                    <td class="text-center"><span class="highlight">{{ number_format($row->highest_marks, 2) }}</span></td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center text-danger">No data found</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center mt-3">
        {{ $listOfData->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>

    <!-- Chart.js Script -->
    <script>
        const districts = @json($chartData->pluck('institute_district'));
        const lowestMarks = @json($chartData->pluck('lowest_marks'));
        const highestMarks = @json($chartData->pluck('highest_marks'));

        const ctx = document.getElementById('districtChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: districts,
                datasets: [
                    {
                        label: 'Lowest Marks',
                        data: lowestMarks,
                        backgroundColor: 'rgba(222, 236, 14, 0.5)'
                    },
                    {
                        label: 'Highest Marks',
                        data: highestMarks,
                        backgroundColor: 'rgba(54, 162, 235, 0.5)'
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: false
                    }
                }
            }
        });
    </script>

</body>
</html>
