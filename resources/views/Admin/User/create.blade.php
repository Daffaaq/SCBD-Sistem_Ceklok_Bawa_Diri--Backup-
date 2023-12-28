@extends('Admin.layouts.index')

@section('container')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Create New User</div>

                    <div class="card-body">
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form method="POST" action="{{ url('/admin/users/store') }}">
                            @csrf

                            <div class="row">
                                <div class="col-md-6">
                                    <!-- Name -->
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="text" name="name" id="name" class="form-control" required>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" name="email" id="email" class="form-control" required>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="form-group">
                                        <label for="no_telp">Phone Number:</label>
                                        <input type="tel" name="no_telp" id="no_telp" class="form-control" required>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <!-- Job Title -->
                                    <div class="form-group">
                                        <label for="jabatan">Job Title:</label>
                                        <input type="text" name="jabatan" id="jabatan" class="form-control" required>
                                    </div>

                                    <!-- Role -->
                                    <div class="form-group">
                                        <label for="role_name">Role:</label>
                                        <select name="role_name" id="role_name" class="form-control" required>
                                            <option value="admin">Admin</option>
                                            <option value="pegawai">Pegawai</option>
                                            <option value="kasubagumum">Kasubagumum</option>
                                        </select>
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group">
                                        <label for="password">Password:</label>
                                        <input type="password" name="password" id="password" class="form-control" required>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="{{ url('/admin/users') }}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Create User</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
