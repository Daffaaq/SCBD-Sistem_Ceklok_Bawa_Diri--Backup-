@extends('Pegawai.layouts.index')

@section('container')
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Attendence Management</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="users-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Waktu Absensi</th>
                            <th>Tanggal Absensi</th>
                            <th>Status Absensi</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('get.recap.attendance') }}",
                columns: [{
                        data: 'attendences_time',
                        name: 'attendences_time'
                    },
                    {
                        data: 'attendences_date',
                        name: 'attendences_date'
                    },
                    {
                        data: 'attendance_status',
                        name: 'attendance_status'
                    },
                    {
                        data: 'attendences_Status',
                        name: 'attendences_Status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                    // Add more columns if needed
                ],

            });
        });

        function deleteAttendance(attendanceId) {
            if (confirm("Are you sure you want to delete this attendance?")) {
                // You can use Ajax to send a request to your delete route
                $.ajax({
                    url: '{{ url('/pegawai/rekap/delete/') }}/' + attendanceId,
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(data) {
                        // Reload the DataTable after successful deletion
                        $('#users-table').DataTable().ajax.reload();
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                // User clicked Cancel, do nothing
            }
        }
    </script>
@endsection
