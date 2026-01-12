<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Requisition Information</title>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">

<style>
body{
    background:#f4f6f9;
    font-family: "Segoe UI", sans-serif;
}
.report-card{
    background:#fff;
    border-radius:10px;
    padding:20px;
    box-shadow:0 4px 10px rgba(0,0,0,.08);
}
.table thead{
    position:sticky;
    top:0;
    z-index:10;
}
.division-cell{
    background:#eef4ff;
    color:#1f3a8a;
    font-weight:700;
    font-size:14px;
}
.division-meta{
    font-size:14px;
    margin-top:6px;
    line-height:1.4;
}
.badge-gen{ background:#dbeafe; color:#1e3a8a; }
.badge-mad{ background:#fef3c7; color:#92400e; }
.badge-tech{ background:#dcfce7; color:#065f46; }

.total-cell{
    background:#edf2ff;
    font-weight:500;
}
tbody tr:hover{
    background:#f1f5ff;
}


@media print {

    body{
        background:white;
        font-size:12px;
    }

    .report-card{
        box-shadow:none;
        border:none;
        padding:0;
    }

    .alert{
        page-break-after:avoid;
        font-size:13px;
    }

    table{
        page-break-inside:auto;
    }

    tr{
        page-break-inside:avoid;
        page-break-after:auto;
    }

    thead{
        display:table-header-group;
    }

    .division-cell{
        background:#f0f4ff !important;
        color:black !important;
    }

    @page{
        size:A4;
        margin:15mm;
    }

    table{
        width:100% !important;
        table-layout:fixed;
    }

    th, td{
        padding:4px !important;
        font-size:11px;
        word-wrap:break-word;
    }

    th:nth-child(1){ width:12%; }
    th:nth-child(2){ width:18%; }
    th:nth-child(3),
    th:nth-child(4),
    th:nth-child(5),
    th:nth-child(6),
    th:nth-child(7){ width:14%; }
}
.mark-values{
    font-size:12px;        /* smaller than badge */
    line-height:1.35;
}


</style>
</head>

<body class="container py-4">



<div class="report-card">
    <h4 class="text-center text-primary fw-bold mb-4">
        📊 Requisition Information For Bangla  
        <small class="text-muted">(Lecturer & Instructor Non-Tech)</small>
    </h4>

    <div class="row mb-3">
   <div class="row mb-3">
    <div class="col">
        <div class="alert alert-primary d-flex justify-content-between align-items-center shadow-sm">
            <div class="fw-bold fs-5">🇧🇩 Country Wide Vacant Summary</div>
            <div>
                    <span class="badge bg-secondary p-2 me-2">
                        Vacant: <strong>{{ $country['total'] }}</strong>
                    </span>

                    <span class="badge badge-gen p-2 me-1">
                        General {{ $country['general'] }}
                    </span>
                    <span class="badge badge-mad p-2 me-1">
                        Madrasha {{ $country['madrasa'] }}
                    </span>
                    <span class="badge badge-tech p-2">
                        Technical {{ $country['technical'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>


    <div class="table-responsive">
    <table class="table table-bordered text-left align-middle">
        <thead class="table-primary">
            <tr>
                <th>Division</th>
                <th>District</th>
                <th>Vacant Info</th>
                <th>Total</th>
                <th>View</th>
                <th>Details</th>
                <th>Remaining- (Marks & Ranks)</th>
                <th>recommended- (Marks & Ranks)</th>
                <th>View Deatils</th>
            </tr>
        </thead>

        <tbody>
        @foreach($data as $division => $info)
            @php $rowspan = count($info['rows']); $first = true; @endphp

            @foreach($info['rows'] as $row)
            <tr>
                @if($first)
                    <td rowspan="{{ $rowspan }}" class="division-cell">
                        {{ $division }}
                    </td>
                @endif
                <td >{{ $row['district'] }}</td>
                <td>
                    <span class="badge badge-gen">Gen: {{ $row['general'] }}</span>
                    <span class="badge badge-mad">Mad: {{ $row['madrasa'] }}</span>
                    <span class="badge badge-tech">Tech: {{ $row['technical'] }}</span>
                          
                </td>
                <td class="fw-bold">{{ $row['sub_total'] }}</td>
                <td>          
                    <button 
                        class="badge badge-tech border-0"
                        style="cursor:pointer"
                        onclick="openVacantModal('{{ $row['district'] }}')">
                        View
                    </button>
                </td>            
                @if($first)
                    <td rowspan="{{ $rowspan }}" class="division-cell text-start">
                        <div class="fw-bold fs-6">{{ $division }}</div>
                        <div class="division-meta">
                            Vacant: <strong>{{ $info['vacant'] }}</strong><br>
                            <span class="badge badge-gen">Gen {{ $info['general'] }}</span>
                            <span class="badge badge-mad">Mad {{ $info['madrasa'] }}</span>
                            <span class="badge badge-tech">Tech {{ $info['technical'] }}</span>
                          
                            
                        </div>
                    </td>

                @endif
                    <td>
                    {{-- REMAINING MARKS --}}
                    @if(!empty($row['remaining_merit_marks']))
                        @php
                            $marks = explode(',', $row['remaining_merit_marks']);
                            $markChunks = array_chunk($marks, 10);
                        @endphp

                        @foreach($markChunks as $index => $group)
                            <div class="d-flex align-items-start mb-1">
                                @if($index == 0)
                                    <span class="badge badge-gen me-1">Marks</span>
                                @else
                                    <span style="width:52px;"></span>
                                @endif

                               <span class="flex-grow-1 text-start mark-values" style="word-break:break-all;">

                                    {{ implode(',', $group) }}
                                </span>
                            </div>
                        @endforeach
                    @endif

                    {{-- REMAINING RANKS --}}
                    @if(!empty($row['ranks']))
                        @php
                            $ranks = explode(',', $row['ranks']);
                            $rankChunks = array_chunk($ranks, 10);
                        @endphp

                        @foreach($rankChunks as $index => $group)
                            <div class="d-flex align-items-start mb-1">
                                @if($index == 0)
                                    <span class="badge badge-tech me-1">Ranks</span>
                                @else
                                    <span style="width:52px;"></span>
                                @endif

                               <span class="flex-grow-1 text-start mark-values" style="word-break:break-all;">

                                    {{ implode(',', $group) }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                </td>

                <td>
                    {{-- RECOMMENDED MARKS --}}
                    @if(!empty($row['recommendation_marks']))
                        @php
                            $marks = explode(',', $row['recommendation_marks']);
                            $markChunks = array_chunk($marks, 20);
                        @endphp

                        @foreach($markChunks as $index => $group)
                            <div class="d-flex align-items-start mb-1">
                                @if($index == 0)
                                    <span class="badge badge-gen me-1">Marks</span>
                                @else
                                    <span style="width:52px;"></span>
                                @endif

                                <span class="flex-grow-1 text-start mark-values" style="word-break:break-all;">

                                    {{ implode(',', $group) }}
                                </span>
                            </div>
                        @endforeach
                    @endif

                    {{-- RECOMMENDED RANKS --}}
                    @if(!empty($row['recommended_ranks']))
                        @php
                            $ranks = explode(',', $row['recommended_ranks']);
                            $rankChunks = array_chunk($ranks, 20);
                        @endphp

                        @foreach($rankChunks as $index => $group)
                            <div class="d-flex align-items-start mb-1">
                                @if($index == 0)
                                    <span class="badge badge-tech me-1">Ranks</span>
                                @else
                                    <span style="width:52px;"></span>
                                @endif

                                <span class="flex-grow-1 text-start mark-values" style="word-break:break-all;">

                                    {{ implode(',', $group) }}
                                </span>
                            </div>
                        @endforeach
                    @endif
                </td>

                 <td>          
                    <button 
                        class="badge badge-gen border-0 ms-1"
                        style="cursor:pointer"
                        onclick="openRecommendedModal('{{ $row['district'] }}')">
                        View
                    </button>

                </td>   
               
            </tr>
            @php $first = false; @endphp
            @endforeach
        @endforeach
        </tbody>
    </table>
    <!-- Vacant Modal -->
    <div class="modal fade" id="vacantModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Vacant List – <span id="modalDistrict"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body" id="vacantModalBody">
                    <div class="text-center text-muted">Loading...</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recommended Modal -->
    <div class="modal fade" id="recommendedModal" tabindex="-1">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Recommended List – <span id="recommendedDistrict"></span>
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="recommendedModalBody">
                    <div class="text-center text-muted">Loading...</div>
                </div>
            </div>
        </div>
    </div>



    </div>
</div>





</body>
</html>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
function openVacantModal(district) {

    document.getElementById('modalDistrict').innerText = district;
    document.getElementById('vacantModalBody').innerHTML =
        '<div class="text-center text-muted">Loading...</div>';

    let modal = new bootstrap.Modal(document.getElementById('vacantModal'));
    modal.show();

    fetch(`/vacancies-by-district?district=${district}`)
        .then(res => res.text())
        .then(html => {
            document.getElementById('vacantModalBody').innerHTML = html;
        })
        .catch(() => {
            document.getElementById('vacantModalBody').innerHTML =
                '<div class="text-danger text-center">Failed to load data.</div>';
        });
}


    function openRecommendedModal(district) {

        document.getElementById('recommendedDistrict').innerText = district;
        document.getElementById('recommendedModalBody').innerHTML =
            '<div class="text-center text-muted">Loading...</div>';

        let modal = new bootstrap.Modal(document.getElementById('recommendedModal'));
        modal.show();

        fetch(`/recommended-by-district?district=${district}`)
            .then(res => res.text())
            .then(html => {
                document.getElementById('recommendedModalBody').innerHTML = html;
            });
    }


</script>
