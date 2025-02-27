<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scraped Data</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h2 class="mb-4">Scraped Data</h2>

        <!-- Back Button to Main Page -->
        <div class="mb-3">
            <a href="{{ route('home') }}" class="btn btn-secondary">Back</a>
        </div>
        
        @if ($scrapedData->isEmpty())
            <div class="alert alert-warning">No data found.</div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Roll No</th>
                        <th>Name</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($scrapedData as $data)
                        <tr>
                            <td>{{ $data->id }}</td>
                            <td>{{ $data->roll_no }}</td>
                            <td>{{ $data->name }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            
            <!-- Pagination Links -->
            <div class="d-flex justify-content-center mt-4">
                {{ $scrapedData->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</body>
</html>
