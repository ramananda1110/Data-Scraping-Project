<!DOCTYPE html>
<html>
<head>
    <title>Not Recommended Merit Summary</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background:#f4f6f9; }
        .card { border-radius:12px; box-shadow:0 6px 16px rgba(0,0,0,.08); }
        .table thead { background:#0d6efd; color:#fff; }
        .badge-bm{ background:#e0f2fe; color:#075985; }
        .badge-gen{ background:#fef3c7; color:#92400e; }
    </style>
</head>
<body class="container py-4">

<div class="card p-4">
    <h4 class="text-center text-primary mb-4">📊 Not Recommended Merit – Marks Wise Summary</h4>

    <table class="table table-bordered text-center align-middle">
        <thead>
            <tr>
                <th>Marks</th>
                <th>Total</th>
                <th>BM</th>
                <th>General</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $row)
            <tr>
                <td class="fw-bold">{{ $row['marks'] }}</td>
                <td>{{ $row['total'] }}</td>
                <td><span class="badge badge-bm">{{ $row['bm'] }}</span></td>
                <td><span class="badge badge-gen">{{ $row['general'] }}</span></td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

</body>
</html>
