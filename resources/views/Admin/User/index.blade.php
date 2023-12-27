@extends('Admin.layouts.index')

@section('container')
    {{-- <div class="custom-main-skills">
        <h1>User Management</h1>
        <table class="table table-bordered" id="users-table">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Jabatan</th>
                    <th>No Telepon</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div> --}}
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">User Management</h6>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered" id="users-table" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Jabatan</th>
                            <th>No Telepon</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="modal fade" id="qrcodeModal" tabindex="-1" role="dialog" aria-labelledby="qrcodeModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="qrcodeModalLabel">QR Code</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div id="qrcodeImage"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="sendEmailBtn">Send</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $('#users-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('users.data') }}',
                columns: [{
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'role_name',
                        name: 'role_name'
                    },
                    {
                        data: 'jabatan',
                        name: 'jabatan'
                    },
                    {
                        data: 'no_telp',
                        name: 'no_telp'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ]
            });
            $('#users-table').on('click', 'a[data-toggle="modal"]', function() {
                var qrcodeImage = $(this).data('qrcode');
                console.log('QR Code Image URL:', qrcodeImage);
                var decodedSVG = atob(qrcodeImage.split(',')[1]);
                $('#qrcodeImage').html(decodedSVG);
                $('#qrcodeModal').modal('show');
            });
            $('#sendEmailBtn').on('click', function() {
                // Dapatkan URL QR Code dari elemen modal
                var qrcodeImage = $('#qrcodeImage img').attr('src');

                // Kirim permintaan AJAX untuk mengirim email
                $.ajax({
                    url: '{{ route('send.qrcode.email') }}', // Sesuaikan dengan rute pengiriman email Anda
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        qrcodeImage: qrcodeImage
                    },
                    success: function(response) {
                        // Tampilkan pesan sukses atau handle response lainnya
                        alert('Email sent successfully!');
                    },
                    error: function(error) {
                        // Tangani kesalahan pengiriman email
                        console.error('Error sending email:', error);
                        alert('Failed to send email!');
                    }
                });
            });
        });
    </script>
@endsection
