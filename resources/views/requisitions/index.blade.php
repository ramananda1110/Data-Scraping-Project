@extends('layouts.app')

@section('title', 'Requisition List')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4 text-center">Requisition List For NTRCA</h2>

    <!-- Filter Form -->
    <div class="card mb-4 shadow-sm">
        <div class="card-body">
            <form method="GET" action="{{ route('requisitions.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" placeholder="Subject" value="{{ request('subject') }}">
                </div>
                <div class="col-md-3">
                    <label for="post_name" class="form-label">Post Name</label>
                    <input type="text" name="post_name" id="post_name" class="form-control" placeholder="Post Name" value="{{ request('post_name') }}">
                </div>
                <div class="col-md-2">
                    <label for="district" class="form-label">District</label>
                    <select name="district" class="form-control">
                    <option value="">Select District</option>
                    <option value="DINAJPUR" {{ request('district') == 'DINAJPUR' ? 'selected' : '' }}>DINAJPUR</option>
                    <option value="THAKURGAON" {{ request('district') == 'THAKURGAON' ? 'selected' : '' }}>THAKURGAON</option>
                    <option value="PANCHAGARH" {{ request('district') == 'PANCHAGARH' ? 'selected' : '' }}>PANCHAGARH</option>
                    <option value="NILPHAMARI" {{ request('district') == 'NILPHAMARI' ? 'selected' : '' }}>NILPHAMARI</option>
                    <option value="RANGPUR" {{ request('district') == 'RANGPUR' ? 'selected' : '' }}>RANGPUR</option>
                    <option value="LALMONIRHAT" {{ request('district') == 'LALMONIRHAT' ? 'selected' : '' }}>LALMONIRHAT</option>
                    <option value="KURIGRAM" {{ request('district') == 'KURIGRAM' ? 'selected' : '' }}>KURIGRAM</option>
                    <option value="GAIBANDHA" {{ request('district') == 'GAIBANDHA' ? 'selected' : '' }}>GAIBANDHA</option>
                    <option value="JOYPURHAT" {{ request('district') == 'JOYPURHAT' ? 'selected' : '' }}>JOYPURHAT</option>
                    <option value="BOGRA" {{ request('district') == 'BOGRA' ? 'selected' : '' }}>BOGRA</option>
                    <option value="NAOGAON" {{ request('district') == 'NAOGAON' ? 'selected' : '' }}>NAOGAON</option>
                    <option value="CHAPAI NAWABGANJ" {{ request('district') == 'CHAPAI NAWABGANJ' ? 'selected' : '' }}>CHAPAI NAWABGANJ</option>
                    <option value="RAJSHAHI" {{ request('district') == 'RAJSHAHI' ? 'selected' : '' }}>RAJSHAHI</option>
                    <option value="NATORE" {{ request('district') == 'NATORE' ? 'selected' : '' }}>NATORE</option>
                    <option value="SIRAJGANJ" {{ request('district') == 'SIRAJGANJ' ? 'selected' : '' }}>SIRAJGANJ</option>
                    <option value="PABNA" {{ request('district') == 'PABNA' ? 'selected' : '' }}>PABNA</option>
                    <option value="JAMALPUR" {{ request('district') == 'JAMALPUR' ? 'selected' : '' }}>JAMALPUR</option>
                    <option value="SHERPUR" {{ request('district') == 'SHERPUR' ? 'selected' : '' }}>SHERPUR</option>
                    <option value="MYMENSINGH" {{ request('district') == 'MYMENSINGH' ? 'selected' : '' }}>MYMENSINGH</option>
                    <option value="NETROKONA" {{ request('district') == 'NETROKONA' ? 'selected' : '' }}>NETROKONA</option>
                    <option value="SUNAMGANJ" {{ request('district') == 'SUNAMGANJ' ? 'selected' : '' }}>SUNAMGANJ</option>
                    <option value="SYLHET" {{ request('district') == 'SYLHET' ? 'selected' : '' }}>SYLHET</option>
                    <option value="HABIGANJ" {{ request('district') == 'HABIGANJ' ? 'selected' : '' }}>HABIGANJ</option>
                    <option value="MOULVIBAZAR" {{ request('district') == 'MOULVIBAZAR' ? 'selected' : '' }}>MOULVIBAZAR</option>
                    <option value="KUSHTIA" {{ request('district') == 'KUSHTIA' ? 'selected' : '' }}>KUSHTIA</option>
                    <option value="MEHERPUR" {{ request('district') == 'MEHERPUR' ? 'selected' : '' }}>MEHERPUR</option>
                    <option value="CHUADANGA" {{ request('district') == 'CHUADANGA' ? 'selected' : '' }}>CHUADANGA</option>
                    <option value="JHENAIDAH" {{ request('district') == 'JHENAIDAH' ? 'selected' : '' }}>JHENAIDAH</option>
                    <option value="MAGURA" {{ request('district') == 'MAGURA' ? 'selected' : '' }}>MAGURA</option>
                    <option value="JESSORE" {{ request('district') == 'JESSORE' ? 'selected' : '' }}>JESSORE</option>
                    <option value="NARAIL" {{ request('district') == 'NARAIL' ? 'selected' : '' }}>NARAIL</option>
                    <option value="SATKHIRA" {{ request('district') == 'SATKHIRA' ? 'selected' : '' }}>SATKHIRA</option>
                    <option value="KHULNA" {{ request('district') == 'KHULNA' ? 'selected' : '' }}>KHULNA</option>
                    <option value="BAGERHAT" {{ request('district') == 'BAGERHAT' ? 'selected' : '' }}>BAGERHAT</option>
                    <option value="DHAKA" {{ request('district') == 'DHAKA' ? 'selected' : '' }}>DHAKA</option>
                    <option value="NARAYANGANJ" {{ request('district') == 'NARAYANGANJ' ? 'selected' : '' }}>NARAYANGANJ</option>
                    <option value="MUNSHIGANJ" {{ request('district') == 'MUNSHIGANJ' ? 'selected' : '' }}>MUNSHIGANJ</option>
                    <option value="RAJBARI" {{ request('district') == 'RAJBARI' ? 'selected' : '' }}>RAJBARI</option>
                    <option value="FARIDPUR" {{ request('district') == 'FARIDPUR' ? 'selected' : '' }}>FARIDPUR</option>
                    <option value="MADARIPUR" {{ request('district') == 'MADARIPUR' ? 'selected' : '' }}>MADARIPUR</option>
                    <option value="SHARIATPUR" {{ request('district') == 'SHARIATPUR' ? 'selected' : '' }}>SHARIATPUR</option>
                    <option value="GOPALGANJ" {{ request('district') == 'GOPALGANJ' ? 'selected' : '' }}>GOPALGANJ</option>
                    <option value="KISHOREGANJ" {{ request('district') == 'KISHOREGANJ' ? 'selected' : '' }}>KISHOREGANJ</option>
                    <option value="TANGAIL" {{ request('district') == 'TANGAIL' ? 'selected' : '' }}>TANGAIL</option>
                    <option value="GAZIPUR" {{ request('district') == 'GAZIPUR' ? 'selected' : '' }}>GAZIPUR</option>
                    <option value="NARSINGDI" {{ request('district') == 'NARSINGDI' ? 'selected' : '' }}>NARSINGDI</option>
                    <option value="MANIKGANJ" {{ request('district') == 'MANIKGANJ' ? 'selected' : '' }}>MANIKGANJ</option>
                    <option value="BARISAL" {{ request('district') == 'BARISAL' ? 'selected' : '' }}>BARISAL</option>
                    <option value="PIROJPUR" {{ request('district') == 'PIROJPUR' ? 'selected' : '' }}>PIROJPUR</option>
                    <option value="JHALOKATHI" {{ request('district') == 'JHALOKATHI' ? 'selected' : '' }}>JHALOKATHI</option>
                    <option value="BARGUNA" {{ request('district') == 'BARGUNA' ? 'selected' : '' }}>BARGUNA</option>
                    <option value="PATUAKHALI" {{ request('district') == 'PATUAKHALI' ? 'selected' : '' }}>PATUAKHALI</option>
                    <option value="BHOLA" {{ request('district') == 'BHOLA' ? 'selected' : '' }}>BHOLA</option>
                    <option value="FENI" {{ request('district') == 'FENI' ? 'selected' : '' }}>FENI</option>
                    <option value="CHITTAGONG" {{ request('district') == 'CHITTAGONG' ? 'selected' : '' }}>CHITTAGONG</option>
                    <option value="COX'S BAZAR" {{ request('district') == "COX'S BAZAR" ? 'selected' : '' }}>COX'S BAZAR</option>
                    <option value="KHAGRACHHARI" {{ request('district') == 'KHAGRACHHARI' ? 'selected' : '' }}>KHAGRACHHARI</option>
                    <option value="RANGAMATI" {{ request('district') == 'RANGAMATI' ? 'selected' : '' }}>RANGAMATI</option>
                    <option value="BANDARBAN" {{ request('district') == 'BANDARBAN' ? 'selected' : '' }}>BANDARBAN</option>
                    <option value="BRAHMANBARIA" {{ request('district') == 'BRAHMANBARIA' ? 'selected' : '' }}>BRAHMANBARIA</option>
                    <option value="COMILLA" {{ request('district') == 'COMILLA' ? 'selected' : '' }}>COMILLA</option>
                    <option value="CHANDPUR" {{ request('district') == 'CHANDPUR' ? 'selected' : '' }}>CHANDPUR</option>
                    <option value="LAKSHMIPUR" {{ request('district') == 'LAKSHMIPUR' ? 'selected' : '' }}>LAKSHMIPUR</option>
                    <option value="NOAKHALI" {{ request('district') == 'NOAKHALI' ? 'selected' : '' }}>NOAKHALI</option>
                </select>                </div>
                <div class="col-md-2">
                    <label for="apply_for" class="form-label">Apply For</label>
                    <select name="apply_for" id="apply_for" class="form-select">
                        <option value="">All</option>
                        <option value="Female only" {{ request('apply_for')=='Female only' ? 'selected' : '' }}>Female only</option>
                        
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="institute_type" class="form-label">Institute Type</label>
                    <select name="institute_type" id="institute_type" class="form-select">
                        <option value="">All</option>
                        <option value="general" {{ request('institute_type')=='general' ? 'selected' : '' }}>General</option>
                        <option value="madrasha" {{ request('institute_type')=='madrasha' ? 'selected' : '' }}>Madrasha</option>

                    </select>
                </div>
                <div class="col-12 text-center mt-3">
                    {{-- Filter form --}}
                    <form action="{{ route('requisitions.index') }}" method="GET" class="d-inline">
                        <button type="submit" class="btn btn-primary me-2">Filter</button>
                    </form>

                    {{-- Reset button --}}
                    <a href="{{ route('requisitions.index') }}" class="btn btn-secondary me-2">Reset</a>

                    {{-- Export PDF form --}}
                    <form action="{{ route('vacancy.exportPdf') }}" method="GET" class="d-inline">
                        <input type="hidden" name="subject" value="{{ request('subject') }}">
                        <input type="hidden" name="post_name" value="{{ request('post_name') }}">
                        <input type="hidden" name="district" value="{{ request('district') }}">
                        <input type="hidden" name="apply_for" value="{{ request('apply_for') }}">
                        <input type="hidden" name="institute_type" value="{{ request('institute_type') }}">

                        <button type="submit" class="btn btn-danger">Export PDF</button>
                    </form>

                </div>

            </form>
        </div>
    </div>

    <!-- Summary Counts (Updated as per Filter) -->
    <div class="alert alert-info mb-4">
        <strong>Total:</strong> {{ $filtered_total }} |
        <strong>Madrasah:</strong> {{ $filtered_madrasah }} |
        <strong>General:</strong> {{ $filtered_general }} |
        <strong>Female Only:</strong> {{ $filtered_female }} | 
        <strong>Lecturer:</strong> {{ $lecturer }} | 
        <strong>Demonstrator:</strong> {{ $demonstrator }}

    </div>

    <!-- Data Table -->
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
            <tr>
                <th>Serial</th>
                <th>Etin ID</th>
                <th>Institute Name</th>
                <th>Subject</th>
                <th>Post For</th>
                <th>District</th>
                <th>Thana</th>
                <th>Apply For</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requisitions as $index => $req)
            <tr>
                <td>{{ ($requisitions->currentPage()-1) * $requisitions->perPage() + $loop->iteration }}</td>
                <td>{{ $req->etin_id }}</td>
                <td>{{ $req->name_of_institute }}</td>
                <td>{{ $req->subject }}</td>
                <td>{{ $req->post_name }}</td>

                <td>{{ $req->district }}</td>
                <td>{{ $req->thana }}</td>
                <td>{{ $req->apply_for }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        {{ $requisitions->appends(request()->query())->links('pagination::bootstrap-4') }}
    </div>
</div>
@endsection
