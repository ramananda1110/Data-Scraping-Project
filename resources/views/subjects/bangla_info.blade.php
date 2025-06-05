<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bangla Subject Info</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
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
    <h1 class="text-center">Bangla Subject Information</h1>

    <!-- Search Form -->
    <form method="GET" action="{{ route('subjects.bangla') }}" class="mb-4">
        <div class="row">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Search by Roll No or Name" value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Search</button>
                <a href="{{ route('subjects.bangla') }}" class="btn btn-secondary">Reset</a>
            </div>
        </div>
    </form>

    <!-- Total Records Info -->
    <div class="alert alert-primary">
        <p><strong>Total Records: </strong> {{ $total }}
        <strong>, Male Records: </strong> {{ $maleCount }}
        <strong>, Female Records: </strong> {{ $femaleCount }}
        <strong>, Final Passed Records: </strong> {{ $passedCount }}
        <strong>, Final Passed Male: </strong> {{ $malePassed }}
        <strong>, Final Passed Female: </strong> {{ $femalePassed }}

    </p>

    </div>

    <!-- Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th> <!-- Serial Number -->
                <th>Roll No</th>
                <th>Name</th>
                <th>Gender</th>
                <th>Final Result</th>

            </tr>
        </thead>
        <tbody>
            @php
                use Illuminate\Support\Str;

                function highlightSearch($text, $search) {
                    if (!$search) return $text;

                    $lowerText = Str::lower($text);
                    $lowerSearch = Str::lower($search);
                    $pos = strpos($lowerText, $lowerSearch);

                    if ($pos !== false) {
                        $originalMatch = substr($text, $pos, strlen($search));
                        return str_replace($originalMatch, '<span class="highlight">' . $originalMatch . '</span>', $text);
                    }
                    return $text;
                }
            @endphp

            @foreach($banglaData as $index => $data)
                <tr>
                    <td>{{ $banglaData->firstItem() + $index }}</td> <!-- Serial Number -->
                    <td>{!! highlightSearch($data->roll_no, request('search')) !!}</td>
                    <td>{!! highlightSearch($data->name, request('search')) !!}</td>
                    <td>{!! highlightSearch($data->gender, request('search')) !!}</td>
                    <td>{!! highlightSearch($data->final_result, request('search')) !!}</td>

                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $banglaData->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</body>
</html>
