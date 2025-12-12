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
    <h1 class="text-center">বাংলা প্রভাষকের সুপারিশ তালিকা :</h1>
     <!-- Filter Form -->

      <!-- Total Records Info -->
    <div class="alert alert-primary">
        <p><strong>Total Recoommended: </strong> {{ $total_recommended }}
        <strong>, Not Recoommended: </strong> {{ $not_recommended }}
        </p>

    </div>
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('recommended.index') }}" class="row g-3">
                
             <div class="col-md-3">
                  <label for="subject" class="form-label">Filter By</label>
                  <input type="text" name="search" class="form-control" placeholder="Filter by Roll or Name or District" value="{{ request('search') }}">
             </div>
             
            <div class="col-md-3">
                  <label for="subject" class="form-label">Filter By Marks</label>
                  <input type="text" name="marks" class="form-control" placeholder="Type marks" value="{{ request('search') }}">
             </div>

            <div class="col-md-2">
                <label for="institute_type" class="form-label">Institute Type</label>
                <select name="institute_type" id="institute_type" class="form-select">
                    <option value="">All</option>
                    <option value="general" {{ request('institute_type')=='general' ? 'selected' : '' }}>General</option>
                    <option value="bm" {{ request('institute_type')=='bm' ? 'selected' : '' }}>BM</option>
                </select>
            </div>
            <div class="col-12 text-center mt-3">
                {{-- Filter form --}}
                <form action="{{ route('recommended.index') }}" method="GET" class="d-inline">
                    <button type="submit" class="btn btn-primary me-2">Filter</button>
                </form>

                {{-- Reset button --}}
                <a href="{{ route('recommended.index') }}" class="btn btn-secondary me-2">Reset</a>

                {{-- Export PDF form --}}
                <form action="{{ route('merit.exportPdf') }}" method="GET" class="d-inline">
                    
                    <!-- <input type="hidden" name="institute_type" value="{{ request('institute_type') }}"> -->
                    <input type="hidden" name="search" value="{{ request('search') }}">
                    <input type="hidden" name="marks" value="{{ request('marks') }}">
                    <button type="submit" class="btn btn-danger">Export PDF</button>
                </form>

            </div>

            </form>
        </div>
    </div>

   

    <!-- Table -->
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>SL:</th> <!-- Serial Number -->
                <th>Roll No</th>
                <th>Rank</th>
                <th>Name</th>
                <th>Marks</th>
                <th>Institute Type</th>
                <th>Recommend Institute</th>
                <th>District</th>
                <th>Thana</th>

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

            @foreach($listOfData as $index => $data)
                <tr>
                    <td>{{ $listOfData->firstItem() + $index }}</td> <!-- Serial Number -->
                    
                    <td>{!! highlightSearch($data->roll, request('search')) !!}</td>
                    <td>{{ $data->id}}</td>
                    <td>{!! highlightSearch($data->applicant_name, request('search')) !!}</td>
                    <td>{{$data->marks}}</td>
                    <td>{{ $data->institute_type}}</td>
                     <td>{!! highlightSearch($data->recommend_institute, request('search')) !!}</td>
                    <td>{{ $data->institute_district}}</td>
                    <td>{{ $data->institute_thana}}</td>
                   

                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $listOfData->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</body>
</html>
