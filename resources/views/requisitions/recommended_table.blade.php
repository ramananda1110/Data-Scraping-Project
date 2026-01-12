<style>

.modal-small-table table{
    font-size:12px;
}

.modal-small-table th,
.modal-small-table td{
    padding:4px 6px;
}
</style>

<div class="modal-body modal-small-table">
    <table class="table table-striped table-bordered">
        <thead class="table-dark">
        <tr>
            <th>#</th>
            <th>Marks</th>
            <th>Merit Position</th>
            <th>Institute</th>

            <th>District</th>
             <th>Thana</th>
        </tr>
    </thead>
    <tbody>
        @foreach($requisitions as $i => $r)
        <tr>
            <td>{{ $i + 1 }}</td>
            <td>{{ $r->marks }}</td>
            <td>{{ $r->id }}</td>
            <td>{{$r->recommend_institute}}</td>
             <td>{{ $r->institute_district }}</td>
            <td>{{ $r->institute_thana }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
