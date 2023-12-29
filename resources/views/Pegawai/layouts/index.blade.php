@include('Pegawai.layouts.sidebar')
@include('Pegawai.layouts.header')
{{-- @include('Admin.layouts_baru.content') --}}

<main>
    @yield('container') <!-- Ini adalah tempat untuk konten yang akan digantikan -->
</main>
@include('Pegawai.layouts.footer')