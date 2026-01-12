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
                <th>EIIN</th>
                <th>Institute</th>
                <th>Authority</th>
                <th>Post</th>
                <th>District</th>
                <th>Thana</th>
                <th>Level</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requisitions as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r->eiin }}</td>
                <td>{{ $r->institute_name }}</td>
                <td>{{ $r->authority }}</td>
                <td>{{ $r->post_name }}</td>
                <td>{{ $r->district }}</td>
                <td>{{ $r->thana }}</td>
                <td>{{ $r->level }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

