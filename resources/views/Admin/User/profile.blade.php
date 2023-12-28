@extends('Admin.layouts.index')

@section('container')

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Edit User</div>

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

                        <form method="POST" action="{{ url("/admin/users/$user->id/update") }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 col-1">
                                    <!-- Name -->
                                    <div class="form-group">
                                        <label for="name">Name:</label>
                                        <input type="text" name="name" id="name" class="form-control"
                                            value="{{ $user->name }}" required>
                                    </div>

                                    <!-- Email -->
                                    <div class="form-group">
                                        <label for="email">Email:</label>
                                        <input type="email" name="email" id="email" class="form-control"
                                            value="{{ $user->email }}" required>
                                    </div>

                                    <!-- Phone Number -->
                                    <div class="form-group">
                                        <label for="no_telp">Phone Number:</label>
                                        <input type="tel" name="no_telp" id="no_telp" class="form-control"
                                            value="{{ $user->no_telp }}" required>
                                    </div>
                                </div>

                                <div class="col-md-6 col-2">
                                    <!-- Job Title -->
                                    <div class="form-group">
                                        <label for="jabatan">Job Title:</label>
                                        <input type="text" name="jabatan" id="jabatan" class="form-control"
                                            value="{{ $user->jabatan }}" required>
                                    </div>

                                    <!-- Role -->
                                    <div class="form-group">
                                        <label for="role_name">Role:</label>
                                        <select name="role_name" id="role_name" class="form-control" required>
                                            <option value="admin" {{ $user->role_name === 'admin' ? 'selected' : '' }}>
                                                Admin</option>
                                            <option value="pegawai" {{ $user->role_name === 'pegawai' ? 'selected' : '' }}>
                                                Pegawai</option>
                                            <option value="kasubagumum"
                                                {{ $user->role_name === 'kasubagumum' ? 'selected' : '' }}>Kasubagumum
                                            </option>
                                        </select>
                                    </div>

                                    <!-- Password -->
                                    <div class="form-group">
                                        <label for="password">New Password:</label>
                                        <input type="password" name="password" id="password" class="form-control">
                                        <small class="text-muted">Leave blank if you don't want to change the
                                            password.</small>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-12">
                                    <a href="{{ url('/admin/users') }}" class="btn btn-secondary">Back</a>
                                    <button type="submit" class="btn btn-primary">Update User</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
