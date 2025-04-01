<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Requisition Information</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body class="container mt-4">
    <h1 class="text-center">Requisition Information</h1>

    <div class="alert alert-primary">
        <p><strong>Total:</strong> {{ $total }}</p>
        <p><strong>Madrasah Total:</strong> {{ $madrasahTotal }}</p>
        <p><strong>General Total:</strong> {{ $generalTotal }}</p>
        <p><strong>Female Only Total:</strong> {{ $femaleOnlyTotal }}</p>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>District</th>
                <th>Total Count</th>
                <th>Madrasah Count</th>
                <th>Female Seat</th>
                <th>General Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($districtCounts as $district)
                <tr>
                    <td>{{ $district->district }}</td>
                    <td>{{ $district->total_count }}</td>
                    <td>{{ $district->madrasa_count }}</td>
                    <td>{{ $district->female_seat }}</td>
                    <td>{{ $district->general_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
